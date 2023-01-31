import Component from "./component";

/**
 * Initialize components defined in HTML with `l-state`
 * @param {HTMLElement|Document} element - Root element to find uninitialized components
 */
export const init = (element: HTMLElement | Document = document): void => {
    const documentBody = element instanceof Document ? element.body : element;
    const stateExpression = documentBody.getAttribute('ui-state');
    const state = new Function(`return ${stateExpression}`)() || {};
    const currentComponent = new Component(state);

    currentComponent.mount(documentBody);
};
