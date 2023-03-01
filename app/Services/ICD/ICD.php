<?php

namespace App\Services\ICD;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;


// API : https://id.who.int/swagger/index.html

class ICD
{
    private $base_url = "https://id.who.int/icd";
    private $client_id;
    private $client_secret;

    public $token;
    public $token_type;
    public $expires_in;

    private $release = "11";
    private $linearization = "mms";
    private $api_version = "v2";
    private $language;
    private $latest_release_id = "2023-01";

    public function __construct($language = "ar")
    {
        $this->client_id = config('icd.client_id');
        $this->client_secret = config('icd.client_secret');
        $this->language = $language;
        $this->generateToken();
    }

    public function empty($response)
    {
        return [
            "message" => $response,
            "success" => false
        ];
    }

    public function success($response)
    {
        return [
            "data" => $response,
            "success" => true,
        ];
    }

    public function generateToken()
    {
        if (cache()->has("icd_token")) {
            $this->token = cache()->get("icd_token");
            return;
        }

        $response = Http::withBasicAuth($this->client_id, $this->client_secret)
            ->asForm()
            ->withHeaders([
                "X-Requested-With" => "XMLHttpRequest",
            ])
            ->post("https://icdaccessmanagement.who.int/connect/token", [
                "grant_type" => "client_credentials",
                "scope" => "icdapi_access"
            ])
            ->json();

        $this->token = $response["access_token"];
        $expires_in = $response["expires_in"];
        $expire_date = now()->addHour();

        $this->token_type = $response["token_type"];
        $this->expires_in = $expires_in;

        cache()->put("icd_token", $this->token, $expire_date);
    }

    public function get($url, $method = "get", $data = [])
    {
        return Http::withToken($this->token)
            ->baseUrl($this->base_url)
            ->withHeaders([
                "API-Version" => $this->api_version,
                "Accept-Language" => $this->language,
                "Accept" => "application/json",
            ])
            ->$method("release/{$this->release}/$url", $data)
            ->json();
    }

    public function all(): Collection
    {
        if (cache()->has("{$this->language}_icd_releases")) {
            return collect(cache()->get("{$this->language}_icd_releases"));
        }

        $response = $this->get("{$this->linearization}");

        if (empty($response) || is_string($response)) {
            return $this->empty($response);
        }

        $releases = collect($response["release"])->map(function ($release) {
            $url = parse_url($release, PHP_URL_PATH);
            $id = explode("/", $url)[4];
            return $id;
        })->toArray();

        cache()->put("{$this->language}_icd_releases", $releases, now()->addMonths(6));

        return $this->success(collect([
            "title" => $response["title"]["@value"],
            "release" => $releases,
        ]));
    }

    public function release($release_id = null)
    {
        $release_id = $release_id ?? $this->latest_release_id;

        if (cache()->has("{$this->language}_icd_release_{$release_id}")) {
            return $this->success(collect(cache()->get("{$this->language}_icd_release_{$release_id}")));
        }

        $response = $this->get("{$release_id}/{$this->linearization}");

        if (empty($response) || is_string($response)) {
            return $this->empty($response);
        }

        $entities = collect($response["child"])->map(function ($entity) {
            $url = parse_url($entity, PHP_URL_PATH);
            $id = explode("/", $url);
            $id = end($id);
            return $id;
        })->toArray();

        cache()->put("{$this->language}_icd_release_{$release_id}", $response, now()->addMonths(6));

        return $this->success(collect([
            "title" => $response["title"]["@value"],
            "entities" => $entities,
        ]));
    }

    public function chapters($release_id = null)
    {
        if (cache()->has("{$this->language}_icd_chapters")) {
            return $this->success(cache()->get("{$this->language}_icd_chapters"));
        }

        $release = $this->release($release_id)["data"] ?? [];

        if (empty($release) || is_string($release)) {
            return $this->empty($release);
        }

        $chapters = collect($release["entities"])->map(function ($chapter) use ($release_id) {
            return $this->entity($chapter, null, $release_id)["data"] ?? [];
        })
            ->filter()
            ->toArray();

        cache()->put("{$this->language}_icd_chapters", $chapters, now()->addMonths(6));

        return $this->success($chapters);
    }

    public function entity($entity_id, $parent_id = null, $release_id = null)
    {
        $release_id = $release_id ?? $this->latest_release_id;

        $residual = null;
        if (!is_numeric($entity_id) && $parent_id) {
            $residual = "/" . $entity_id;
            $entity_id  = $parent_id;
        }

        $response = $this->get("{$release_id}/{$this->linearization}/{$entity_id}{$residual}");

        if (empty($response) || is_string($response)) {
            return $this->empty($response);
        }

        $parent = $response["parent"][0] ?? null;
        if ($parent) {
            $parent = parse_url($parent, PHP_URL_PATH);
            $parent = explode("/", $parent);
            $parent = [
                "release_id" => $parent[4] ?? null,
                "entity_id" => $parent[6] ?? null,
            ];
        }

        $entities = collect($response["child"] ?? [])->map(function ($entity) {
            $url = parse_url($entity, PHP_URL_PATH);
            $id = explode("/", $url);
            $id = end($id);
            return $id;
        })->toArray();

        $definition = $response["definition"]["@value"] ?? null;

        $exclusion = collect($response["exclusion"] ?? [])->map(function ($exclusion) {
            $label = $exclusion["label"]["@value"];
            $linearizationReference = $exclusion["linearizationReference"];
            $linearizationReference = parse_url($linearizationReference, PHP_URL_PATH);
            $linearizationReference = explode("/", $linearizationReference);
            $linearizationReference = [
                "release_id" => $linearizationReference[4] ?? null,
                "entity_id" => $linearizationReference[6] ?? null,
            ];

            return [
                "label" => $label,
                "data" => $linearizationReference,
            ];
        })->toArray();

        $id = explode("/", $response['source'] ?? null);
        $id = end($id);
        if (empty($id)) $id = null;

        return $this->success(collect([
            "id" => $id,
            "code" => $response["code"],
            "type"  => $response["classKind"],
            "title" => $response["title"]["@value"],
            "parent" => $parent ?? [],
            "entities" => $entities,
            "definition" => $definition,
            "exclusion" => $exclusion,
        ]));
    }

    public function entityFromCode($code, $release_id = null)
    {
        $release_id = $release_id ?? $this->latest_release_id;

        $response = $this->get("{$release_id}/{$this->linearization}/codeinfo/{$code}");

        if (empty($response) || is_string($response)) {
            return $this->empty($response);
        }

        $stemId = $response["stemId"];
        $stemId = parse_url($stemId, PHP_URL_PATH);
        $stemId = explode("/", $stemId);

        $release_id = $stemId[4];
        $entity_id = $stemId[6];
        $residual = $stemId[7] ?? null;
        $parent_id = null;
        if ($residual && !is_numeric($residual)) {
            $parent_id = $entity_id;
            $entity_id = $residual;
        }

        return $this->entity($entity_id, $parent_id, $release_id);
    }

    public function search($title, $chapterFilter = null, $flexisearch = false)
    {
        $title = "$title%";
        $includeKeywordResult = true;
    }
}
