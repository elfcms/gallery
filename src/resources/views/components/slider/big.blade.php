<link rel="stylesheet" href="/vendor/elfcms/gallery/css/components/slider/big.css">
<div class="slider-wrapper">
    <div id="slider"></div>
</div>
<script src="/vendor/elfcms/gallery/js/kjs.js"></script>
<script>
const dataString = '{!! $gallery->sliderJson() ?? '[{}]' !!}';
const data = JSON.parse(dataString);
let slider = new KJS('#slider', data, {
    count: 1,
    gap: 2,
    step: 1,
    infinity: true,
    //single: false,
    //title: true,
    //description: true,
    //arrows: false,
    //auto: 4,
    //zoom: true,
    dots: true,
    track: {
        //direction: 'vertical'
    },
    responsive: {
        768: {
            dots: false
        }
    }
    //type: 'big'
});
window.onresize = () => {
    let active = slider.active;
    slider.init();
    slider.step = active;
    slider.setStep();
}
</script>
