import { init } from './core';
export * from './@types';

document.addEventListener('DOMContentLoaded', () => {
    init();

    document.querySelectorAll(`[ui-lazy]`).forEach(el => {
        el.removeAttribute(`ui-lazy`);
    });
});
