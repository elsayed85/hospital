@isset($pageConfigs)
    {!! updatePageConfig($pageConfigs) !!}
@endisset
@php
    $configData = appClasses();
@endphp

@isset($configData['layout'])
    @include(
        $configData['layout'] === 'horizontal'
            ? 'layouts.horizontalLayout'
            : ($configData['layout'] === 'blank'
                ? 'layouts.blankLayout'
                : 'layouts.contentNavbarLayout'))
@endisset
