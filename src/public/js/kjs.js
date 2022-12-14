class KJS {

    constructor (container,data,parameters={}) {
        if (!container || !data || typeof data !== 'object') {
            return false;
        }
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }

        this.container = container;
        this.data = data;
        this.itemsCount = data.length;

        this.param = {
            count:      parameters?.count       ?? 1,
            infinity:   parameters?.infinity    ?? false,
            gap:        parameters?.gap         ?? 0,
            single:     parameters?.single      ?? false,
            loop:       parameters?.loop        ?? false,
            auto:       parameters?.auto        ?? false,
            effect:     parameters?.effect      ?? 'none',
            zoom:       parameters?.zoom        ?? false,
            step:       parameters?.step        ?? 1,
            arrows:     parameters?.arrows      ?? true,
            dots:       parameters?.dots        ?? false,
            size:       parameters?.string      ?? 'contain',
            singleSize: parameters?.singleSize  ?? 'contain',
            title:      parameters?.title ?? false,
            description:parameters?.description  ?? false,
            track:      parameters?.track       ?? {
                direction:  parameters?.track?.direction    ?? 'horizontal',
                size:       parameters?.track?.size         ?? 'auto',
                crossSize:  parameters?.track?.crossSize    ?? 'sqare'
            },
            classes: {
                item:       parameters?.classes?.item   ?? 'slide-item',
                track:      parameters?.classes?.track  ?? 'slide-track',
                single:     parameters?.classes?.single ?? 'slide-slingle',
                box:        parameters?.classes?.box    ?? 'slide-box'
            },
            responsive: parameters?.responsive ?? false,
        }

        this.defaultParam = JSON.parse(JSON.stringify(this.param));

        if (this.param.step > this.param.count) {
            this.param.step = this.param.count;
        }

        this.items = [];
        this.track;
        this.trackInner;
        this.single;
        this.box;
        this.containerRect;
        this.itemSize;
        this.trackSize;
        this.viewSize;
        this.boxSize;
        this.sizeName = 'width';
        this.crossSizeName = 'height';
        this.shiftName = 'X';
        this.step = 0;
        this.shift = 0;
        this.lastStep = false;
        this.firstStep = true;
        this.maxStep;
        this.autoTimeout = 5;
        this.autoInterval;
        this.active = 0;
        this.fullActive = 0;
        this.trackViewSize;
        this.nextButton;
        this.prevButton;
        this.tiltleBox;
        this.descriptionBox;
        this.pictureBox;
        this.responsiveSize = Infinity;
        this.responsiveParam;
        this.lightBoxBackground;
        this.lightBoxCenter;
        this.lightBoxInfo;
        this.lightBoxTitle;
        this.lightBoxDescription;
        this.lightBoxPicture;
        this.lightBoxNext;
        this.lightBoxPrev;
        this.lightBoxClose;

        this.trackSwipe = {
            start: 0,
            current: 0,
            end: 0,
            min: 50,
        }

        this.fullSwipe = {
            start: 0,
            current: 0,
            end: 0,
            min: 50,
        }

        if (typeof this.container === 'object' && this.container instanceof HTMLElement) {
            this.setParams();
            this.init();
            
            if (this.param.responsive) {
                window.addEventListener('resize',()=>{
                    let lastResponsiveSize = this.responsiveSize;
                    this.setParams();
                    console.log(lastResponsiveSize, this.responsiveSize)
                    if (lastResponsiveSize != this.responsiveSize) {
                        console.log('init')
                        this.init();
                    }
                    //this.init();
                });
            }
        }
        else {
            return false;
        }
    }

    init() {

        //console.log(window.innerWidth)

        //this.responsiveSize = window.innerWidth;

        this.container.innerHTML = '';

        this.containerRect = this.container.getBoundingClientRect();

        if (this.param.track.direction == 'vertical') {
            this.boxSize = this.containerRect.height;
            this.sizeName = 'height';
            this.crossSizeName = 'width';
            this.shiftName = 'Y';
        }
        else {
            this.boxSize = this.containerRect.width;
            this.param.track.direction = 'horizontal'
            this.sizeName = 'width';
            this.crossSizeName = 'height';
            this.shiftName = 'X';
        }

        this.container.append(this.createBox(this.createTrack()));
        this.build();

    }

    build() {

        this.track.append(this.createList());
        let trackRect = this.track.getBoundingClientRect();

        if (this.param.track.direction == 'vertical') {
            this.trackViewSize = trackRect.height;
        }
        else {
            this.trackViewSize = trackRect.width;
        }

        this.viewSize = this.param.gap * (this.param.count - 1);
        this.itemSize = parseInt((this.trackViewSize - (this.viewSize)) / this.param.count);
        this.trackSize = this.itemSize * this.itemsCount + this.param.gap * (this.itemsCount - 1);
        console.log('1',this.itemSize);
        this.maxStep = Math.ceil((this.itemsCount - this.param.count) / this.param.step);


        this.trackInner.style.setProperty(this.sizeName, this.trackSize + 'px')
        this.data.forEach((item,i) => {
            item.num = i;
            let element = this.createItem(item);
            element.addEventListener('click',()=>{
                this.active = i;
                this.setActive();
            });
            if (this.param.zoom && !this.param.single) {
                element.addEventListener('click',()=>{
                    this.showLightBox();
                });
            }
            this.trackInner.append(element);
            this.items[i] = element;
        });

        if (this.param.single) {
            this.box.classList.add('with-single');
            this.box.append(this.createSingle(''));
        }
        else {
            if (this.param.title) {
                this.box.append(this.createTitle());
            }
            if (this.param.description) {
                this.box.append(this.createDescription());
            }
        }

        if (this.param.auto !== false) {
            this.auto();
        }
        if (this.param.zoom) {
            this.setLightBox();
        }

        this.setActive();
    }

    auto() {
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
        }
        if (this.param.auto === false) {
            return false;
        }
        if (this.param.auto === true) {
            this.param.auto = this.autoTimeout;
        }
        else {
            this.param.auto = parseFloat(this.param.auto);
        }
        if (isNaN(this.param.auto) || this.param.auto <= 0) {
            return false;
        }
        if (this.param.auto < 100) {
            this.param.auto = this.param.auto * 1000;
        }
        this.param.infinity = true;

        this.autoInterval = setInterval(() => {
            this.next();
        }, this.param.auto)
    }

    showLightBox() {
        if (this.param.zoom && this.lightBoxBackground) {
            this.fullActive = this.active;
            this.setFullActive();
            this.lightBoxBackground.classList.remove('slider-full-hidden');
        }
    }

    hideLightBox() {
        if (this.lightBoxBackground) {
            this.lightBoxBackground.classList.add('slider-full-hidden');
        }
    }

    setLightBox(container = document.body) {
        this.lightBoxBackground = document.createElement('div');
        this.lightBoxBackground.classList.add('slider-full-wrapper','slider-full-hidden');
        this.lightBoxCenter = document.createElement('div');
        this.lightBoxCenter.classList.add('slider-full-center');
        this.lightBoxInfo = document.createElement('div');
        this.lightBoxInfo.classList.add('slider-full-info');
        this.lightBoxTitle = document.createElement('div');
        this.lightBoxTitle.classList.add('slider-full-title');
        this.lightBoxDescription = document.createElement('div');
        this.lightBoxDescription.classList.add('slider-full-description');
        this.lightBoxPicture = document.createElement('div');
        this.lightBoxPicture.classList.add('slider-full-picture');
        this.lightBoxNext = document.createElement('div');
        this.lightBoxNext.classList.add('slider-full-next');
        this.lightBoxPrev = document.createElement('div');
        this.lightBoxPrev.classList.add('slider-full-prev');
        this.lightBoxClose = document.createElement('div');
        this.lightBoxClose.classList.add('slider-full-close');
        this.lightBoxClose.addEventListener('click',()=>{
            this.lightBoxBackground.classList.add('slider-full-hidden');
        });
        this.lightBoxNext.addEventListener('click',()=>{
            this.nextLightBox();
        });
        this.lightBoxPrev.addEventListener('click',()=>{
            this.prevLightBox();
        });

        let img = this.data[this.active].full ?? this.data[this.active].img ?? this.data[this.fullActive].thumb;


        /* swipe */

        this.lightBoxPicture.addEventListener('touchstart',(e) => {
            this.fullSwipe.start = this.fullSwipe.current = this.fullSwipe.end = e.touches[0]['page'+this.shiftName];
            console.log('start', this.fullSwipe.start, this.fullSwipe.current, this.fullSwipe.end)
        },false);

        this.lightBoxPicture.addEventListener('touchend',(e) => {

            this.fullSwipe.end = this.fullSwipe.current;
            let touchMoving = this.fullSwipe.end - this.fullSwipe.start;

            if (touchMoving > this.fullSwipe.min) {
                this.prevLightBox();
            }
            else if (touchMoving < -this.fullSwipe.min) {
                this.nextLightBox();
            }
            else {
                //this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+this.shift+'px)');
            }
            this.fullSwipe.start = 0;
            this.fullSwipe.current = 0;
            this.fullSwipe.end = 0;
        },false);

        this.lightBoxPicture.addEventListener('touchmove',(e) => {
            this.fullSwipe.current = e.touches[0]['page'+this.shiftName];
            /* let touchMoving = this.fullSwipe.current - this.fullSwipe.start + this.shift;
            console.log(touchMoving)
            this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+touchMoving+'px)'); */
        },false);

        this.lightBoxPicture.addEventListener('touchcancel',(e) => {
            this.fullSwipe.start = 0;
            this.fullSwipe.current = 0;
            this.fullSwipe.end = 0;
            //this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+this.shift+'px)');
        },false);

        /* /swipe */

        this.lightBoxPicture.style.backgroundImage = "url('" + img + "')";
        this.lightBoxPicture.append(this.lightBoxNext);
        this.lightBoxPicture.append(this.lightBoxPrev);
        this.lightBoxCenter.append(this.lightBoxPicture);
        this.lightBoxInfo.append(this.lightBoxTitle);
        this.lightBoxInfo.append(this.lightBoxDescription);
        this.lightBoxCenter.append(this.lightBoxInfo);
        this.lightBoxBackground.append(this.lightBoxCenter);
        this.lightBoxBackground.append(this.lightBoxClose);
        
        container.append(this.lightBoxBackground);
    }

    nextLightBox() {
        if (this.fullActive >= this.itemsCount-1) {
            if (this.param.infinity) {
                this.fullActive = 0;
            }
            else {
                return false;
            }
        }
        else {
            this.fullActive++;
        }

        this.setFullActive();
    }

    prevLightBox() {
        if (this.fullActive <= 0) {
            if (this.param.infinity) {
                this.fullActive = this.itemsCount-1;
            }
            else {
                return false;
            }
        }
        else {
            this.fullActive--;
        }

        this.setFullActive();
    }

    setFullActive() {
        //console.log(this.fullActive)
        let img = this.data[this.fullActive].full ?? this.data[this.fullActive].img ?? this.data[this.fullActive].thumb;
        this.lightBoxPicture.style.backgroundImage = "url('" + img + "')";
    }

    setActive() {
        if (!this.active) {
            this.active = 0;
        }
        if (this.active >= this.itemsCount) {
            this.active = this.itemsCount-1;
        }
        else if (this.active < 0) {
            this.active = 0;
        }
        if (!this.items[this.active]) {
            return false;
        }
        
        this.items[this.active].classList.add('active');
        this.items.forEach(item => {
            if (item != this.items[this.active]) {
                item.classList.remove('active');
            }
        });

        if (this.pictureBox) {
            this.pictureBox.style.backgroundImage = "url('" + this.items[this.active].dataset.img + "')";
        }
        if (this.param.title && this.tiltleBox) {
            this.tiltleBox.innerHTML = this.items[this.active].title;
        }
        if (this.param.description && this.descriptionBox) {
            this.descriptionBox.innerHTML = this.items[this.active].dataset.description;
        }
        
        return this.items[this.active];
    }

    setStep() {
        let shift = this.step * this.param.step * (this.itemSize + this.param.gap);

        if (this.step > 0) {
            this.firstStep = false;
        }
        else {
            this.firstStep = true;
        }

        if (this.step >= this.maxStep) {
            shift = this.trackSize - this.trackViewSize;

            console.log('2',this.trackSize);
            this.lastStep = true;
        }
        else {
            this.lastStep = false;
        }

        this.shift = -shift;

        this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+this.shift+'px)');

        this.setActive();
    }

    next() {
        if (this.lastStep) {
            if (this.param.infinity !== true) {
                return false;
            }
            this.step = 0;
            this.active = 0;
        }
        else {
            this.step++;
            this.active += this.param.step;
        }
        this.setStep();
    }

    prev() {
        if (this.firstStep) {
            if (this.param.infinity !== true) {
                return false;
            }
            this.step = this.maxStep;
            this.active = this.itemsCount;
        }
        else {
            this.step--;
            this.active -= this.param.step;
        }
        this.setStep();
    }

    createItem(itemData) {
        let data = {
            img: itemData?.img ?? itemData?.thumb ?? null,
            thumb: itemData?.thumb ?? itemData?.img ?? null,
            title: itemData?.title ?? null,
            description: itemData?.description ?? null,
            html: itemData?.html ?? null,
            num: itemData?.num ?? null,
            full: itemData?.full ?? null,
        }

        let img = data.img;
        if (this.param.single || this.param.count == 1) {
            img = data.thumb;
        }

        let item = document.createElement('div');
        item.classList.add(this.param.classes.item);
        if (img) {
            item.style.backgroundImage = "url('" + img + "')";
        }
        
        if (data.title) {
            item.title = data.title;
        }
        if (data.img) {
            item.dataset.img = data.img;
        }
        if (data.full) {
            item.dataset.full = data.full;
        }
        if (data.description) {
            item.dataset.description = data.description;
        }
        
        if (data.html) {
            item.innerHTML = data.html;
        }
        console.log('i',this.itemSize)
        if (this.itemSize) {
            item.style.setProperty(this.sizeName, this.itemSize+'px');
            let image = new Image();
            image.src = img
            image.onload = () => {
                let k = image[this.sizeName] / this.itemSize;
                let crossSize = Math.round(image[this.crossSizeName] / k);
                if (crossSize) {
                    item.style.setProperty(this.crossSizeName, crossSize+'px');
                }
            }
        }
        
        return item;
    }

    createList() {
        let trackInner = document.createElement('div');
        trackInner.classList.add(this.param.classes.track + '-inner');
        trackInner.classList.add(this.param.track.direction);
        this.trackInner = trackInner;
        
        return this.trackInner;
    }

    createArrows() {
        let prev = document.createElement('div');
        prev.classList.add('slide-arrow-prev');
        let next = document.createElement('div');
        next.classList.add('slide-arrow-next');
        let prevContent,
            nextContent;
        if (typeof this.param.arrows === 'object') {
            if (Array.isArray(this.param.arrows)) {
                prevContent = this.param.arrows[0];
                nextContent = this.param.arrows[1];
            }
            else {
                if (this.param.arrows.prev) {
                    prevContent = this.param.arrows.prev;
                }
                if (this.param.arrows.next) {
                    prevContent = this.param.arrows.next;
                }
            }
        }
        if (prevContent) {
            prev.insertAdjacentHTML('afterbegin',prevContent);
            next.insertAdjacentHTML('beforeend',nextContent);
        }

        this.prevButton = prev;
        this.nextButton = next;

        this.prevButton.addEventListener('click',()=>{
            this.prev();
        });
        this.nextButton.addEventListener('click',()=>{
            this.next();
        });
        return true;
    }

    createTrack(content) {
        let trackBox = document.createElement('div');
        trackBox.classList.add(this.param.classes.track+'-box');
        trackBox.classList.add(this.param.track.direction);
        let track = document.createElement('div');
        track.classList.add(this.param.classes.track);
        track.classList.add(this.param.track.direction);
        if (this.param.arrows) {
            track.classList.add('with-arrows')
            if (this.createArrows()) {
                if (this.prevButton) {
                    trackBox.append(this.prevButton);
                }
                if (this.nextButton) {
                    trackBox.append(this.nextButton);
                }
            }
        }
        trackBox.append(track);
        if (content) trackBox.append(content);
        this.track = track;

        /* swipe */

        this.track.addEventListener('touchstart',(e) => {
            this.trackSwipe.start = this.trackSwipe.current = this.trackSwipe.end = e.touches[0]['page'+this.shiftName];
            console.log('start', this.trackSwipe.start, this.trackSwipe.current, this.trackSwipe.end)
        },false);

        this.track.addEventListener('touchend',(e) => {

            this.trackSwipe.end = this.trackSwipe.current;
            let touchMoving = this.trackSwipe.end - this.trackSwipe.start;

            if (touchMoving > this.trackSwipe.min) {
                this.prev();
            }
            else if (touchMoving < -this.trackSwipe.min) {
                this.next();
            }
            else {
                this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+this.shift+'px)');
            }
            this.trackSwipe.start = 0;
            this.trackSwipe.current = 0;
            this.trackSwipe.end = 0;
        },false);

        this.track.addEventListener('touchmove',(e) => {
            this.trackSwipe.current = e.touches[0]['page'+this.shiftName];
            let touchMoving = this.trackSwipe.current - this.trackSwipe.start + this.shift;
            console.log(touchMoving)
            this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+touchMoving+'px)');
        },false);

        this.track.addEventListener('touchcancel',(e) => {
            this.trackSwipe.start = 0;
            this.trackSwipe.current = 0;
            this.trackSwipe.end = 0;
            this.trackInner.style.setProperty('transform','translate'+this.shiftName+'('+this.shift+'px)');
        },false);

        /* /swipe */
        console.log('track')

        this.trackBox = trackBox;
        return this.trackBox;
        
    }

    createTitle() {
        if (this.param.title) {
            this.tiltleBox = document.createElement('div');
            this.tiltleBox.classList.add('slide-title');
            return this.tiltleBox;
        }
        else {
            return false;
        }
    }

    createDescription() {
        if (this.param.description) {
            this.descriptionBox = document.createElement('div');
            this.descriptionBox.classList.add('slide-description');
            return this.descriptionBox;
        }
        else {
            return false;
        }
    }

    createSingle(content) {
        let single = document.createElement('div');
        single.classList.add(this.param.classes.single);
        this.pictureBox = document.createElement('div');
        this.pictureBox.classList.add('slide-picture');
        single.append(this.pictureBox);
        if (content) this.pictureBox.append(content);
        if (this.param.title) {
            /* single.classList.add('with-title');
            this.tiltleBox = document.createElement('div');
            this.tiltleBox.classList.add('slide-title'); */
            single.append(this.createTitle());
        }
        if (this.param.description) {
            /* single.classList.add('with-description');
            this.descriptionBox = document.createElement('div');
            this.descriptionBox.classList.add('slide-description'); */
            single.append(this.createDescription());
        }
        if (this.param.zoom && this.param.single) {
            single.addEventListener('click',()=>{
                this.showLightBox();
            });
        }
        this.single = single;
        return this.single;
    }

    createBox(content) {
        let box = document.createElement('div');
        box.classList.add(this.param.classes.box);
        box.classList.add(this.param.track.direction);
        if (content) box.append(content);
        this.box = box;
        return this.box;
    }

    setParams() {
        if (this.param.responsive) {
            this.responsiveSize = Infinity;
            this.responsiveParam = null;
            for (let size in this.param.responsive) {
                size = parseInt(size);
                if (window.innerWidth <= size) {
                    console.log('01',size,window.innerWidth)
                    this.responsiveSize = size;
                    this.responsiveParam = this.param.responsive[this.responsiveSize];
                    break;
                }
                else {
                    console.log('02',size,window.innerWidth)
                }
            }
            console.log(this.param.count,this.responsiveParam?.count,this.defaultParam.count)
            this.param.count       = this.responsiveParam?.count        ?? this.defaultParam.count;
            this.param.infinity    = this.responsiveParam?.infinity     ?? this.defaultParam.infinity;
            this.param.gap         = this.responsiveParam?.gap          ?? this.defaultParam.gap;
            this.param.single      = this.responsiveParam?.single       ?? this.defaultParam.single;
            this.param.loop        = this.responsiveParam?.loop         ?? this.defaultParam.loop;
            this.param.auto        = this.responsiveParam?.auto         ?? this.defaultParam.auto;
            this.param.effect      = this.responsiveParam?.effect       ?? this.defaultParam.effect;
            this.param.step        = this.responsiveParam?.step         ?? this.defaultParam.step;
            this.param.arrows      = this.responsiveParam?.arrows       ?? this.defaultParam.arrows;
            this.param.dots        = this.responsiveParam?.dots         ?? this.defaultParam.dots;
            this.param.size        = this.responsiveParam?.size         ?? this.defaultParam.size;
            this.param.singleSize  = this.responsiveParam?.singleSize   ?? this.defaultParam.singleSize;
            this.param.title       = this.responsiveParam?.title        ?? this.defaultParam.title;
            this.param.description = this.responsiveParam?.description  ?? this.defaultParam.description;
            this.param.track       = this.responsiveParam?.track        ?? this.defaultParam.track;
            console.log(this.param.count,this.responsiveParam?.count,this.defaultParam.count)

        }

    }

}

