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


function galleryTagFormInit() {
    const tagForm = document.querySelectorAll('.tag-form-wrapper')
    let tagList = null;

    async function getTagList () {
        if (tagList !== null && typeof tagList == 'object') {
            return tagList;
        }
        let response = await fetch('/admin/gallery/tags',{headers: {'X-Requested-With': 'XMLHttpRequest'}});
        tagList = await response.json();
        return tagList;
    }
    getTagList();

    function addTagToList (listBox,input,item) {
        const check = document.querySelector('.tag-item-box[data-id="'+item.id+'"]')
        if (!check) {
            const elem = `<div class="tag-item-box" data-id="${item.id}">
                <span class="tag-item-name">${item.name}</span>
                <span class="tag-item-remove" onclick="removeTagFromList(this)">&#215;</span>
                <input type="hidden" name="tags[]" value="${item.id}">
            </div>`;
            listBox.insertAdjacentHTML('beforeend',elem)
        }
    }


    if (tagForm) {
        tagForm.forEach(wrapBox => {
            let listBox = wrapBox.querySelector('.tag-list-box')
            let promptBox = wrapBox.querySelector('.tag-prompt-list')
            let inputBox = wrapBox.querySelector('.tag-input-box')
            let input = wrapBox.querySelector('input.tag-input')
            let button = wrapBox.querySelector('button.tag-add-button')
            input.addEventListener('input',function(){
                const th = this
                const list = tagList.filter((item) => {
                    if (item.name.indexOf(th.value) > -1 && th.value != '') {
                        return item
                    }
                })
                promptBox.innerHTML = '';
                list.forEach(item => {
                    let prompt = document.createElement('div');
                    prompt.classList.add('tag-prompt-item');
                    prompt.dataset.id = item.id;
                    prompt.innerHTML = item.name;
                    prompt.addEventListener('click',function(){
                        addTagToList (listBox,input,item)
                    });
                    promptBox.append(prompt);
                })
            })
            button.addEventListener('click',function(){
                const data = JSON.stringify({name:input.value});
                const token = document.querySelector("input[name='_token']").value;
                fetch('/admin/gallery/tags/addnotexist',{
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                    },
                    credentials: 'same-origin',
                    body: data
                }).then(
                    (result) => result.json()
                ).then (
                    (data) => {
                        if (data.result && data.data) {
                            if (data.result == 'success' && data.data.id) {
                                tagList.push(data.data)
                            }
                            if (data.data.id) {
                                addTagToList (listBox,input,data.data)
                            }
                        }
                    }
                ).catch(error => {
                    //
                });
            })
        })
    }
}
