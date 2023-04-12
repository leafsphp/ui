import Dom from './../engine/dom';
import { error } from './../utils/error';

export default class Connection {
    protected static headers: Record<string, string>;

    public static connect(
        type: string,
        uiData: Record<string, any>,
        dom: typeof Dom
    ) {
        const component: Element = uiData.element.closest('[ui-state]');
        const componentData = component.getAttribute('ui-state') ?? '{}';

        const payload = {
            type,
            payload: {
                params: [],
                method: uiData.method,
                methodArgs: uiData.methodArgs,
                data: componentData,
            }
        };

        return fetch(
            `${window.location.href}?_leaf_ui_config=${JSON.stringify(
                payload
            )}`,
            {
                method: uiData.config.method,
                // This enables "cookies".
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'text/html, application/xhtml+xml',
                    'X-Leaf-UI': 'true',

                    // set Custom Headers
                    ...this.headers,

                    // We'll set this explicitly to mitigate potential interference from ad-blockers/etc.
                    Referer: window.location.href
                }
            }
        ).then(async response => {
            if (response.ok) {
                response.text().then(response => {
                    const data = JSON.parse(response);
                    window._leafUIConfig.data = data.state;
                    dom.diff(data.html, document.body!);
                });
            } else {
                error(await response.text().then(res => res));
            }
        });
    }
}
