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
function editItem(action,currentItem,isEdit=true){
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
    fetch(action,{
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
                        fetch(createForm.action,{
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
                        console.log('--');
                        console.log(data);
                        console.log(isEdit);
                        console.log(currentItem);
                                if (data.result && data.result == 'success' && data.data) {
                                    const itemsBox = document.querySelector('.gallery-items-content');
                                    if (isEdit) {
                                        currentItem.dataset.slug = data.data.slug;
                                        currentItem.style.order = data.data.position;
                                        currentItem.title = '__("basic::elf.edit") ' + data.data.name;
                                        const h5 = currentItem.querySelector('h5');
                                        if (h5) {
                                            h5.innerHTML = data.data.name;
                                        }
                                        const img = currentItem.querySelector('img');
                                        if (img) {
                                            img.src = data.data.image;
                                        }
                                        currentItem.href = '/admin/gallery/{{$gallery->slug}}/items/'+data.data.slug+'/edit';
                                    }
                                    else if (itemsBox) {
                                        const newItem = document.createElement('div');
                                        newItem.href = '/admin/gallery/{{$gallery->slug}}/items/'+data.data.slug+'/edit';
                                        newItem.classList.add('gallery-item-tile','gallery-item-element');
                                        newItem.dataset.slug = data.data.slug;
                                        newItem.dataset.id = data.data.id;
                                        newItem.style.order = data.data.position;
                                        newItem.title = '__("basic::elf.edit") ' + data.data.name;
                                        const img = document.createElement('img');
                                        if (img) {
                                            img.src = data.data.image;
                                            newItem.append(img)
                                        }
                                        const h5 = document.createElement('h5');
                                        if (h5) {
                                            h5.innerHTML = data.data.name;
                                            newItem.append(h5)
                                        }
                                        newItem.addEventListener('click',function(e){
                                            e.preventDefault();
                                            editItem(newItem.href,newItem);
                                        });
                                        itemsBox.append(newItem);
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
}
// Edit item
const editItemElements = document.querySelectorAll('.gallery-item-element');
if (editItemElements) {
    editItemElements.forEach(editElement => {
        editElement.addEventListener('click',function(e){
            e.preventDefault();
            editItem(this.href,this);
        });
    });
}
// Create item
const createButton = document.querySelector('#galleryitemcreate');
if (createButton) {
    createButton.addEventListener('click',function(e){
        e.preventDefault();
        editItem(this.href,this,false);
        /* const createBoxWrapper = document.createElement('div');
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
                                            } *
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
        }); */
    });
}
</script>
