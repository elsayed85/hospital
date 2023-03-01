<?php

namespace App\Services;

use App\Models\ProjectFile;


class Locker
{
    protected $source_code;
    protected $hash;
    protected $file;
    protected $directory;
    protected $secret;
    protected $depth = 1;
    protected $files = [];
    protected $db_files;

    public function setDirectory($directory)
    {
        $this->directory = base_path($directory);
        return $this;
    }

    public function setFile($file)
    {
        $this->file = base_path($file);
        return $this;
    }

    public function withoutFile($file)
    {
        $this->files = array_filter($this->files, function ($f) use ($file) {
            return $f != $file;
        });
        return $this;
    }

    public function withFile($file)
    {
        $this->files[] = $file;
        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }

    public function setDbFiles($files)
    {
        $this->db_files = $files;
        return $this;
    }

    public function getSourceCode()
    {
        return file_get_contents($this->file);
    }

    public function getHash()
    {
        $hash = "";
        for ($i = 0; $i < $this->depth; $i++) {
            $hash .= hash_hmac('sha256', $this->getSourceCode(), $this->secret);
        }
        return $hash;
    }

    public function onlyPhpFiles($directory = null)
    {
        $files = [];

        if (empty($directory)) {
            $directory = $this->directory;
        }


        if (!is_dir($directory)) {
            return [];
        }

        foreach (scandir($directory) as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }

            $path = $directory . "/" . $file;

            if (is_dir($path)) {
                $files = array_merge($files, $this->onlyPhpFiles($path));
            } else {
                if (pathinfo($path, PATHINFO_EXTENSION) == "php") {
                    $file = str_replace(base_path(), "", $path);
                    // remove first slash
                    $file = substr($file, 1);
                    $files[] = $file;
                }
            }
        }

        $this->files = $files;

        return $files;
    }

    public function getFilesWithoutBasePath()
    {
        return array_map(function ($file) {
            return str_replace(base_path(), "", $file);
        }, $this->files);
    }

    public function compute($forDB = false)
    {
        if (!empty($this->directory)) {
            $files = $this->onlyPhpFiles();
        } else {
            $files = [$this->file];
        }

        $results = [];

        foreach ($files as $file) {
            $this->setFile($file);
            $hash_db = $this->db_files->where("file", $file)->first()->hash ?? null;
            $hash = $this->getHash();
            $equal = $hash_db ? hash_equals($hash, $hash_db) : false;
            $human_readable_file_name = str_replace("/", " > ", $file);
            if ($forDB) {
                $results[] = [
                    "file" => $file,
                    "hash" => $hash,
                ];
            } else {
                $results[] = [
                    "file" => $file,
                    "human_readable_file_name" => $human_readable_file_name,
                    "modified" => !$equal,
                ];
            }
        }

        return $results;
    }

    public function getInstalledModulesWithHash()
    {
        $modules = [];
        foreach (scandir(base_path("Modules")) as $module) {
            if ($module == "." || $module == "..") {
                continue;
            }

            $modules[] = $module;
        }

        $hash = hash_hmac('sha256', implode(",", $modules), $this->secret);

        return [
            "modules" => $modules,
            "hash" => $hash,
        ];
    }
}
