{{-- <div class="dragndrop-wrapper">

</div> --}}
<div class="gallery-items-content">
    <a href="{{route('admin.gallery.items.create',$gallery)}}" class="gallery-item-add gallery-item-tile" id="galleryitemcreate" title="{{__('gallery::elf.create_item')}}"></a>
    <div class="gallery-item-tile" style="order:1;">1</div>
    <div class="gallery-item-tile" style="order:2;">2</div>
    <div class="gallery-item-tile" style="order:0;">3</div>
    <div class="gallery-item-tile" style="order:0;">4</div>
    <div class="gallery-item-tile" style="order:1;">5</div>
    <div class="gallery-item-tile" style="order:-1;">6</div>
    <div class="gallery-item-tile" style="order:3;">7</div>
    <div class="gallery-item-tile" style="order:4;">8</div>
    <div class="gallery-item-tile" style="order:1;">9</div>
    <div class="gallery-item-tile" style="order:5;">10</div>
    <div class="gallery-item-tile" style="order:1;">11</div>
    <div class="gallery-item-tile" style="order:1;">12</div>
    <div class="gallery-item-tile" style="order:12;">13</div>
    <div class="gallery-item-tile" style="order:15;">14</div>
    <div class="gallery-item-tile" style="order:22;">15</div>
    <div class="gallery-item-tile" style="order:11;">16</div>
</div>
<div class="gallery-items-buttons">
    <button class="default-btn submit-button" disabled>{{ __('basic::elf.save') }}</button>
</div>


<script>
const createButton = document.querySelector('#galleryitemcreate');
//const createBox = document.querySelector('#galleryitemcreatebox');
if (createButton) {
    createButton.addEventListener('click',function(e){
        e.preventDefault();
        const createBoxWrapper = document.createElement('div');
        createBoxWrapper.classList.add('gallery-item-create-popup-wrapper');
        const createBox = document.createElement('div');
        createBox.classList.add('gallery-item-create-popup-box');
        createBoxWrapper.append(createBox);
        const closeBox = document.createElement('a');
        closeBox.classList.add('gallery-item-create-popup-close');
        closeBox.title = '{{__("basic::elf.cancel")}}';
        closeBox.addEventListener('click',function(e){
            e.preventDefault();
            createBoxWrapper.innerHTML = '';
            createBoxWrapper.remove();
        });
        createBox.append(closeBox);
        document.body.append(createBoxWrapper);
        fetch('/admin/gallery/{{$gallery->slug}}/items/create',{
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            credentials: 'same-origin',
        }).then(
            (result) => result.text()
        ).then (
            (data) => {
                if (data) {
                    createBox.insertAdjacentHTML('afterbegin',data);
                    const createSubmit = createBox.querySelector('[type="submit"]');
                    if (createSubmit) {
                        createSubmit.addEventListener('click',function(e){
                            e.preventDefault();
                            console.log('submit')
                        });
                    }
                }
            }
        ).catch(error => {
            //
        });
    });
}


const dragndropBox = document.querySelector('.dragndrop-wrapper');
if (dragndropBox) {
    dragndropBox.addEventListener('dragover',function(e){
        e.preventDefault();
        this.classList.add('dragover')
    });
    dragndropBox.addEventListener('dragleave',function(e){
        e.preventDefault();
        this.classList.remove('dragover')
    });
    dragndropBox.addEventListener('drop',function(e){
        e.preventDefault();
        console.log(e.dataTransfer.files)
        this.classList.remove('dragover')
        this.classList.add('droped')
        setTimeout(() => {
            this.classList.remove('droped')
        }, 2000);
    });
}
</script>
