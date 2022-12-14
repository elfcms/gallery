<link rel="stylesheet" href="/vendor/elfcms/gallery/css/components/slider/big.css">
<div class="slider-wrapper">
    <div id="slider"></div>
</div>
<script src="/vendor/elfcms/gallery/js/kjs.js"></script>
<script>
const dataString = '{!! $gallery->data ?? '[{}]' !!}';
const data = JSON.parse(dataString);
new KJS('#slider', data, {
    count: 1,
    gap: 2,
    step: 1,
    infinity: true,
    single: false,
    //title: true,
    //description: true,
    //arrows: false,
    auto: 4,
    zoom: true,
    track: {
        //direction: 'vertical'
    },
    responsive: {
        700: {
            count: 2,
            step: 2
        },
        400: {
            count: 1,
            step: 1
        },
    }
})
</script>
