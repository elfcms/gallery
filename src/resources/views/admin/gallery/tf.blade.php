<form action="/admin/gal/test" method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf
    <input type="file" name="pic" id="pic" multiple>
</form>
<button type="button" id="submit">Submit</button>
<div id="result"></div>
<div id="progress"></div>
<script>
const form = document.querySelector('form');
const button = document.querySelector('#submit');
const token = document.querySelector('input[name="_token"]').value;
const resultBox = document.querySelector('#result');
const progressBox = document.querySelector('#progress');

button.addEventListener('click',function(e){
    e.preventDefault();
    resultBox.innerHTML = '';
    progressBox.innerHTML = '';
    ajax(form.action,{
        method: form.method,
        formData: new FormData(form),
        headers: {
            'X-CSRF-TOKEN': token
        },
        resolve: function(result) {
            console.log(result.response);
            resultBox.innerHTML = result.response;
        },
        progress: function(event) {
            console.log(event);
            if (event.lengthComputable) {
                progressBox.innerHTML = `Получено ${event.loaded} из ${event.total} байт`;
            } else {
                progressBox.innerHTML = `Получено ${event.loaded} байт`;
            }
        },
        uploadResolve: null,
    });
});

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
</script>
