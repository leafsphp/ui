import Component from './component';

/**
 * Initialize Your Leaf UI root component
 * @param {HTMLElement|Document} element - Root element to find uninitialized components
 */
export const init = (element: HTMLElement | Document = document): void => {
    const leafUI = new Component();
    const rootElement = element instanceof Document ? element.body : element;

    leafUI.mount(rootElement);
};
