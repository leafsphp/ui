import { init } from './core';
import { monkeyPatchDomSetAttributeToAllowAtSymbols } from './utils/reset';
export * from './@types';

document.addEventListener('DOMContentLoaded', () => {
    monkeyPatchDomSetAttributeToAllowAtSymbols();
    init();

    document.querySelectorAll('[ui-lazy]').forEach(el => {
        el.removeAttribute('ui-lazy');
    });
});
