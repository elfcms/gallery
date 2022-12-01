{{-- <div class="dragndrop-wrapper">

</div> --}}
<div class="gallery-items-content dragndrop-wrapper" draggable="false" data-uploadtext="{{__('gallery::elf.file_upload')}}">
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
        editElement.draggable = true;
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
    });
}

const submitItems = document.querySelector('.gallery-items-buttons .submit-button');


const dragndropBox = document.querySelector('.dragndrop-wrapper');
/* if (dragndropBox) {
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
} */

/*
class galleryDrag {

    isMoved = false
    dragElement = null
    classNames = {
        item: 'gallery-item-element',
        dragged: 'dragged'
    }
    submitButton = null
    box = null

    constructor (element, params = {}) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (!element) {
            return false;
        }
        this.box = element;
        if (params.classNames && params.classNames.item) {
            this.classNames.item = params.classNames.item
        }
        if (params.classNames && params.classNames.dragged) {
            this.classNames.dragged = params.classNames.dragged
        }
        if (params.submitButton) {
            if (typeof params.submitButton === 'string') {
                this.submitButton = document.querySelector(params.submitButton);
            }
            else if (params.submitButton instanceof HTMLElement) {
                this.submitButton = params.submitButton;
            }
        }
        this.init();
    }

    init () {

        const th = this;

        this.box.addEventListener('dragstart', function (e) {
            //e.preventDefault();
            th.dragElement = e.target;
            th.dragElement.classList.add(th.classNames.dragged);
        });

        this.box.addEventListener('drop',function(e){
            //e.preventDefault();
            console.log(e.target);
            console.log(e.dataTransfer.files.length)
        });

        this.box.addEventListener('dragend', function (e) {
            //e.preventDefault();
            //console.log(e.target);
            if (th.submitButton) {
                th.submitButton.disabled = false;
            }
            th.dragElement.classList.remove(th.classNames.dragged);
            const items = th.box.querySelectorAll(th.classNames.item);
            if (items && th.isMoved) {
                if (th.submitButton) {
                    th.submitButton.disabled = false;
                }
                let i = 1;
                items.forEach(item => {
                    item.style.order = i;
                    i++;
                });
            }
        });

        this.box.addEventListener('dragover', function (e) {
            //e.preventDefault();

            //const activeElement = element.querySelector('.'+th.classNames.dragged);
            //console.log(e.dataTransfer.files.length)

            if (!th.dragElement) {
                return false;
            }
            /* const startPrev = activeElement.querySelector
            const startNext = activeElement.nextElementSibling *
            //console.log(element.childNodes.indexOf(activeElement));
            const currentElement = e.target;
            /* let isDraggable = th.dragElement !== currentElement &&
            currentElement.classList.contains(th.classNames.item);

            if (!isDraggable) {
                return false;
            } *

            /* const nextElement = getNextElement(e.clientX, currentElement);
            const prevElement = nextElement.previousElementSibling
            console.log('p',prevElement)
            console.log('n',nextElement)
    *
            //console.log(currentElement)

            let insertAfter = false;

            const currentElementCoord = currentElement.getBoundingClientRect();
            const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

            if (e.clientX > currentElementCenter) {
                insertAfter = true;
            }

            //console.log(currentElement)
            //console.log(th.dragElement)

            //return false;

            const nextElement = th.getNextElement(e.clientX, currentElement);

            if (
                nextElement &&
                th.dragElement === nextElement.previousElementSibling ||
                th.dragElement === nextElement
            ) {
                //nextElement.classList.remove('dropready');
                return false;
            }

            th.isMoved = true;

            if (insertAfter) {
                if (currentElement.nextElementSibling) {
                    th.dragElement.style.order = currentElement.nextElementSibling.style.order;
                    th.box.insertBefore(th.dragElement, currentElement.nextElementSibling);
                }
                else {
                    th.dragElement.style.order = Number.parseInt(currentElement.style.order) + 1
                    th.box.append(th.dragElement);
                }
            }
            else {
                th.dragElement.style.order = currentElement.style.order
                th.box.insertBefore(th.dragElement, currentElement);
            }



            //element.insertBefore(activeElement, nextElement);
        });
    }



    getNextElement (cursorPosition, currentElement) {
        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

        const nextElement = (cursorPosition < currentElementCenter) ?
            currentElement :
            currentElement.nextElementSibling;

        return nextElement;
    };
} */

function filesUpload (files) {
    if (!files) {
        return false;
    }
    //
    console.log('upload');
    console.log(files);
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
        //console.log(e.target === element);
        //console.log(e.dataTransfer.files.length)
        element.classList.remove('filedrag');
        if (e.target === element && e.dataTransfer.files && e.dataTransfer.files.length) {
            filesUpload(e.dataTransfer.files);
        }
        /* this.classList.remove('dragover')
        this.classList.add('droped')
        setTimeout(() => {
            this.classList.remove('droped')
        }, 2000); */
    });

    element.addEventListener('dragend', function (e) {
        e.preventDefault();
        element.classList.remove('filedrag');
        //console.log(e.target);
        e.target.classList.remove('dragged');
        const items = element.querySelectorAll('.gallery-item-element');
        if (items && isMoved) {
            submitItems.disabled = false;
            let i = 1;
            items.forEach(item => {
                item.style.order = i;
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
        //console.log(e.dataTransfer.files.length)
        //console.log(e.dataTransfer.types)

        if (!activeElement) {
            return false;
        }
        /* const startPrev = activeElement.querySelector
        const startNext = activeElement.nextElementSibling */
        //console.log(element.childNodes.indexOf(activeElement));
        const currentElement = e.target;
        let isDraggable = activeElement !== currentElement &&
        currentElement.classList.contains('gallery-item-element');

        if (!isDraggable) {
            return false;
        }

        /* const nextElement = getNextElement(e.clientX, currentElement);
        const prevElement = nextElement.previousElementSibling
        console.log('p',prevElement)
        console.log('n',nextElement)
 */
        //console.log(currentElement)

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
            //nextElement.classList.remove('dropready');
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



        //element.insertBefore(activeElement, nextElement);
    });
}

dndInit(dragndropBox);
//const galDrag = new galleryDrag(dragndropBox);

</script>
