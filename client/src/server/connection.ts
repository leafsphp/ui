import Dom from './../engine/dom';
import { error } from './../utils/error';

export default class Connection {
    protected static headers: Record<string, string>;

    public static connect(
        type: string,
        uiData: Record<string, any>,
        dom: typeof Dom
    ) {
        const pageState: Record<string, any> = {};
        const component: HTMLElement = uiData.element.closest('[ui-state]');
        const componentData = JSON.parse(component?.getAttribute('ui-state') ?? '{}');
        const components = document.querySelectorAll('[ui-state]');

        components.forEach((i) => {
            const attr = JSON.parse(i.getAttribute('ui-state') ?? '{}');
            pageState[attr.key] = attr;
        });

        const payload = {
            type,
            payload: {
                params: [],
                method: uiData.method,
                methodArgs: uiData.methodArgs,
                component: componentData?.key,
                data: pageState,
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
                    dom.diff(
                        data.html,
                        component.nodeName === 'HTML' || !component
                            ? document.body!
                            : component
                    );
                });
            } else {
                error(await response.text().then(res => res));
            }
        });
    }
}
