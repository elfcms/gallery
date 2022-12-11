{{-- <div class="dragndrop-wrapper">

</div> --}}
<form action="{{ route('admin.gallery.items.groupSave',$gallery) }}" method="POST" name="gallery-item-list" id="gallery-item-list">
    @method('POST')
    @csrf
    <div class="gallery-items-content dragndrop-wrapper" draggable="false" data-uploadtext="{{__('gallery::elf.file_upload')}}">
        <a href="{{route('admin.gallery.items.create',$gallery)}}" class="gallery-item-add gallery-item-tile" id="galleryitemcreate" title="{{__('gallery::elf.create_item')}}"></a>
        @foreach ($gallery->items as $item)
            @include('gallery::admin.gallery.items.content.item')
        @endforeach
    </div>
    <div class="gallery-items-buttons">
        <button class="default-btn submit-button" disabled>{{ __('basic::elf.save') }}</button>
    </div>
</form>


<script>
const submitItems = document.querySelector('.gallery-items-buttons .submit-button');
const editItemElements = document.querySelectorAll('.gallery-item-element');
const createButton = document.querySelector('#galleryitemcreate');
const dragndropBox = document.querySelector('.dragndrop-wrapper');
const itemListForm = document.querySelector('#gallery-item-list');

if (itemListForm) {
    itemListForm.addEventListener('submit',function(e){
        e.preventDefault();
        return false;
    });
}

if (submitItems) {
    submitItems.addEventListener('click',function(e){
        e.preventDefault();
        let form = itemListForm;
        if (!form) {
            form = this.closest('form');
        }
        if (!form) {
            return false;
        }
        const itemsForDelete = document.querySelectorAll('.fordelete');
        if (itemsForDelete && itemsForDelete.length > 0) {
            popup({
                title:'{{__("gallery::elf.deleting_items")}}',
                content:'{{__("gallery::elf.marked_items_will_be_removed")}}',
                buttons:[
                    {
                        title:'{{__("basic::elf.delete")}}',
                        class:'default-btn delete-button',
                        callback: [
                            function(){
                                itemListSave (form);
                            },
                            'close'
                        ]
                    },
                    {
                        title:'{{__("basic::elf.cancel")}}',
                        class:'default-btn cancel-button',
                        callback:'close'
                    }
                ],
                class:'danger'
            });
        }
        else {
            itemListSave (form);
        }
    });
}

function preloadSet(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!(element instanceof HTMLElement) && element !== document) {
        return false;
    }
    const preloader = document.createElement('div')
    preloader.classList.add('preload-wrapper');
    preloader.insertAdjacentHTML('beforeend','<div class="preload-box"><div></div><div></div><div></div></div>');
    element.append(preloader);

    return preloader;
}

function preloadUnset(preloader) {
    if (typeof preloader === 'string') {
        preloader = document.querySelector(preloader);
    }
    if (!(preloader instanceof HTMLElement)) {
        return false;
    }
    preloader.remove();
}

function itemListSave(form) {
    if (!form || !form.action) {
        return false;
    }
    const formData = new FormData(form);
    const itemsBox = document.querySelector('.gallery-items-content');
    let preloader = preloadSet('.big-container');
    fetch(form.action,{
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        credentials: 'same-origin',
        body: formData
    }).then(
        (result) => result.json()
    ).then (
        (data) => {
            //console.log(data);
            if (data.result && data.result == 'success') {
                const forDeletes = document.querySelectorAll('.gallery-item-element.fordelete');
                if (forDeletes) {
                    forDeletes.forEach(delElem => {
                        delElem.remove();
                    });
                }
                submitItems.disabled = true;
                popup({
                    title:'{{__("gallery::elf.edit_gallery")}}',
                    content:'{{__("gallery::elf.gallery_edited_successfully")}}',
                    buttons:[
                        {
                            title:'OK',
                            class:'default-btn submit-button',
                            callback:'close'
                        }
                    ],
                    class:'submit'
                });
            }
            else {
                if (data.errors && data.message) {
                    popup({
                        title:'{{__("gallery::elf.error")}}',
                        content:data.message,
                        buttons:[
                            {
                                title:'OK',
                                class:'default-btn submit-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    });
                }
            }
            preloadUnset(preloader);
        }
    ).catch(error => {
        preloadUnset(preloader);
    });
}

function filesUpload (files) {
    if (!files) {
        return false;
    }
    //
    console.log('upload');
    console.log(files);

    for(key in files) {
        //if (key != 'length' && key != 'item' && files[key].type) {
        if (files[key] instanceof File) {
            console.log(files[key]);
            fileUpload(files[key]);
        }
    };
}

function fileUpload (file) {
    if (!(file instanceof File)) {
        return false;
    }
    const formData = new FormData();
    formData.append('file', file, file.name);
    ajax('/admin/gal/test',{
        method: 'post',
        formData: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        resolve: function(result) {
            console.log('resolve',result.response);
        },
        progress: function(e) {
            //console.log(e);
            if (e.lengthComputable) {
                //progressBox.innerHTML = `Получено ${event.loaded} из ${event.total} байт`;
                //console.log(`${file.name}: Получено ${e.loaded} из ${e.total} байт`)
                console.log(Math.round(e.loaded / e.total * 100) + '%');
            }/*  else {
                //progressBox.innerHTML = `Получено ${event.loaded} байт`;
                console.log(`${file.name}: Получено ${e.loaded} байт`)
            } */
        },
        uploadResolve: null,
    });
}

function dndInit(element) {

    let isMoved = false;

    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (!element) {
        return false;
    }
    element.addEventListener('dragstart', function (e) {
        e.target.classList.add('dragged');
    });
    element.addEventListener('dragleave', function (e) {
        element.classList.remove('filedrag');
    });

    element.addEventListener('drop',function(e){
        e.preventDefault();
        element.classList.remove('filedrag');
        if (e.target === element && e.dataTransfer.files && e.dataTransfer.files.length) {
            filesUpload(e.dataTransfer.files);
        }
    });

    element.addEventListener('dragend', function (e) {
        e.preventDefault();
        element.classList.remove('filedrag');
        e.target.classList.remove('dragged');
        const items = element.querySelectorAll('.gallery-item-element');
        if (items && isMoved) {
            submitItems.disabled = false;
            let i = 1;
            items.forEach(item => {
                item.style.order = i;
                let positionInput = item.querySelector('input[data-field="position"]');
                if (positionInput) {
                    positionInput.value = i;
                }
                i++;
            });
        }
    });

    const getNextElement = (cursorPosition, currentElement) => {
        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

        const nextElement = (cursorPosition < currentElementCenter) ?
            currentElement :
            currentElement.nextElementSibling;

        return nextElement;
    };

    element.addEventListener('dragover', function (e) {
        e.preventDefault();

        if (e.dataTransfer.types && e.dataTransfer.types.includes('Files')) {
            element.classList.add('filedrag');
        }
        else {
            element.classList.remove('filedrag');
        }

        const activeElement = element.querySelector('.dragged');

        if (!activeElement) {
            return false;
        }

        const currentElement = e.target;
        let isDraggable = activeElement !== currentElement &&
        currentElement.classList.contains('gallery-item-element');

        if (!isDraggable) {
            return false;
        }

        let insertAfter = false;

        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

        if (e.clientX > currentElementCenter) {
            insertAfter = true;
        }

        const nextElement = getNextElement(e.clientX, currentElement);

        if (
            nextElement &&
            activeElement === nextElement.previousElementSibling ||
            activeElement === nextElement
        ) {
            return false;
        }

        isMoved = true;

        if (insertAfter) {
            if (currentElement.nextElementSibling) {
                activeElement.style.order = currentElement.nextElementSibling.style.order;
                element.insertBefore(activeElement, currentElement.nextElementSibling);
            }
            else {
                activeElement.style.order = Number.parseInt(currentElement.style.order) + 1
                element.append(activeElement);
            }
        }
        else {
            activeElement.style.order = currentElement.style.order
            element.insertBefore(activeElement, currentElement);
        }

    });
}

function ajax(url, params = {}) {
    if (!params.method && typeof params.method !== 'sting')  {
        params.method = 'GET';
    }
    params.method = params.method.toUpperCase();
    if (params.method != 'GET' && params.method != 'POST') {
        params.method = 'GET';
    }
    if (!params.resolve) {
        params.resolve = (result) => {
            return result;
        };
    }

    let promise = new Promise(function(resolve, reject) {
        let request = new XMLHttpRequest();
        request.open(params.method, url);
        if (params.headers && typeof params.headers === 'object') {
            Object.entries(params.headers).forEach((entry) => {
                const [key, value] = entry;
                if (key && value) {
                    request.setRequestHeader(key,value);
                }
            });
        }

        if (params.progress && typeof params.progress === 'function') {
            request.upload.onprogress = function(event) {
                params.progress(event);
            }
        }

        if (params.uploadResolve && typeof params.uploadResolve === 'function') {
            request.upload.onload = function() {
                params.uploadResolve(request);
            }
        }

        if (params.progress && typeof params.progress === 'function') {
            request.onprogress = function(event) {
                params.progress(event);
            }
        }

        request.onload = function() {
            if (request.status == 200) {
                resolve(request);
            }
            else {
                reject(Error(request.statusText));
            }
        };

        request.onerror = function() {
            reject(Error("Network Error"));
        };

        if (params.method == 'POST' && params.formData) {
            request.send(params.formData);
        }
        else {
            request.send();
        }
    });

    if (params.resolve && typeof params.resolve === 'function') {
        promise.then(params.resolve)
    }
}


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
                                        const newItem = document.createElement('a');
                                        newItem.href = '/admin/gallery/{{$gallery->slug}}/items/'+data.data.slug+'/edit';
                                        newItem.classList.add('gallery-item-tile','gallery-item-element');
                                        newItem.dataset.slug = data.data.slug;
                                        newItem.dataset.id = data.data.id;
                                        newItem.style.order = data.data.position;
                                        newItem.title = '__("basic::elf.edit") ' + data.data.name;
                                        newItem.draggable = true;
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
                                        const deleteBox = document.createElement('div');
                                        deleteBox.classList.add('delete-item-box');
                                        deleteBox.title = '{{__("basic::elf.delete")}}'
                                        const deleteInp = document.createElement('input');
                                        deleteInp.type = 'checkbox';
                                        deleteInp.name = `item[${data.data.id}][delete]`;
                                        deleteInp.id = `item_${data.data.id}_delete`;
                                        deleteInp.dataset.field = 'delete';
                                        //deleteInp.onclick = 'event.stopPropagation()';
                                        deleteInp.addEventListener('click',function(e){
                                            e.stopPropagation();
                                            submitItems.disabled = false;
                                            if (this.checked) {
                                                newItem.classList.add('fordelete')
                                            }
                                            else {
                                                newItem.classList.remove('fordelete')
                                            }
                                        });
                                        deleteBox.append(deleteInp);
                                        deleteBox.insertAdjacentHTML('beforeend','<i></i>');
                                        newItem.append(deleteBox);
                                        newItem.addEventListener('click',function(e){
                                            e.preventDefault();
                                            editItem(newItem.href,newItem);
                                        });
                                        newItem.insertAdjacentHTML('beforeend',`<input type="hidden" name="item[${data.data.id}][position]" value="${data.data.position}" data-field="position">`);

                                        itemsBox.append(newItem);
                                    }
                                    createBoxWrapper.remove();
                                }
                                else {
                                    if (data.errors && data.message) {
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
if (editItemElements) {
    editItemElements.forEach(editElement => {
        editElement.draggable = true;
        editElement.addEventListener('click',function(e){
            e.preventDefault();
            editItem(this.href,this);
        });
        const deleteInput = editElement.querySelector('input[data-field="delete"]');
        if (deleteInput) {
            deleteInput.addEventListener('click',function(e){
                e.stopPropagation();
                submitItems.disabled = false;
                if (this.checked) {
                    editElement.classList.add('fordelete')
                }
                else {
                    editElement.classList.remove('fordelete')
                }
            });
        }
    });
}
// Create item
if (createButton) {
    createButton.addEventListener('click',function(e){
        e.preventDefault();
        editItem(this.href,this,false);
    });
}

dndInit(dragndropBox);

</script>
