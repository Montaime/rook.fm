import {WindowState} from "@/util.js";

export class Window {
    id = 0;
    title = '';

    decoration = 0;
    style = '';
    visibility = WindowState.Closed;

    x = 0;
    y = 0;
    z = 0;

    height = 0;
    width = 0;

    minimHeight = 0;
    minimWidth = 0;
    minimX = 0;
    minimY = 0;

    resizable = true;

    component;

    constructor(component, options = null) {
        this.component = component;

        if (typeof(options) === 'object') {
            let fillable = [
                'title',
                'visibility',
                'style',
                'x',
                'y',
                'z',
                'height',
                'width',
                'resizable',
            ];

            console.log('setting', options['title'], this['title'])

            for (let key of fillable) this[key] = options[key] ?? this[key];
        }
    }
}
