{{-- <div class="dragndrop-wrapper">

</div> --}}
<div class="gallery-items-content">
    <a href="{{route('admin.gallery.items.create',$gallery)}}" class="gallery-item-add gallery-item-tile" id="galleryitemcreate" title="{{__('gallery::elf.create_item')}}"></a>
    @foreach ($gallery->items as $item)
        @include('gallery::admin.gallery.items.content.item')
    @endforeach
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
                    const createForm = createBox.querySelector('form');
                    if (createForm) {
                        const previewInput = document.querySelector('#preview')
                        if (previewInput) {
                            inputFileImg(previewInput)
                        }
                        const imageInput = document.querySelector('#image')
                        if (imageInput) {
                            inputFileImg(imageInput)
                        }
                        const thumbnailInput = document.querySelector('#thumbnail')
                        if (thumbnailInput) {
                            inputFileImg(thumbnailInput)
                        }
                        autoSlug('.autoslug')

                        //add editor
                        runEditor('#description')
                        runEditor('#additional_text')

                        const submitButton = createForm.querySelector('[type="submit"]');
                        const newSubmitButton = submitButton.cloneNode(true);
                        const submitButtonBox = submitButton.parentNode;
                        //infoMessageBox.id = 'infomessagebox';
                        submitButtonBox.append(newSubmitButton);
                        submitButton.remove();
                        const infoMessageBox = document.createElement('div');
                        submitButtonBox.append(infoMessageBox);

                        function formSubmit () {
                            if (infoMessageBox) {
                                infoMessageBox.innerHTML = '';
                            }
                            const formData = new FormData(createForm);
                            fetch('{{route("admin.gallery.items.store",$gallery)}}',{
                                method: 'POST',
                                headers: {
                                    //'Accept': 'application/json',
                                    //'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                credentials: 'same-origin',
                                body: formData
                            }).then(
                                (result) => result.json()
                            ).then (
                                (data) => {
                                    if (data.result && data.result == 'success' && data.data) {
                                        const itemsBox = document.querySelector('.gallery-items-content')
                                        if (itemsBox) {
                                            itemsBox.insertAdjacentHTML('beforeend',data.data);
                                        }
                                        createBoxWrapper.remove();
                                    }
                                    else {
                                        if (data.errors && data.message) {
                                            /* let dataErrors = [],
                                                i = 0;
                                            for (key in data.errors) {
                                                if (data.errors[key].length) {
                                                    data.errors[key].forEach(message => {
                                                        dataErrors[i] = message;
                                                        i++;
                                                    });
                                                }
                                            } */
                                            let errorString = '<div class="alert alert-danger">'+data.message+'</div>';
                                            infoMessageBox.insertAdjacentHTML('beforeend',errorString);
                                        }
                                    }
                                }
                            ).catch(error => {
                                //
                            });
                        }
                        setTimeout (function(){
                            createForm.addEventListener('submit',function(e){
                                e.preventDefault();
                                formSubmit();
                            });
                            if (newSubmitButton) {
                                newSubmitButton.addEventListener('click',function(e){
                                    e.preventDefault();
                                    formSubmit();
                                });
                            }
                        },500)
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
