import Dom from './../engine/dom';
import { LeafUIConfig } from './../@types/core';
import { error } from './../utils/error';

export default class Connection {
    protected static headers: Record<string, string>;

    public static connect(methodName: string, leafUIConfig: LeafUIConfig, dom: typeof Dom) {
        const payload = {
            updates: {
                type: 'callMethod',
                payload: {
                    method: methodName,
                    component: leafUIConfig.component,
                    data: leafUIConfig.data,
                    params: []
                }
            }
        };

        return fetch(`${window.location.href}${leafUIConfig.path}`, {
            method: 'POST',
            body: JSON.stringify(payload),
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
        }).then(async response => {
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

    //    public sendMessage(message) {
    //        // Forward the query string for the ajax requests.

    //            .then(response => {
    //                if (response.ok) {
    //                    response.text().then(response => {
    //                        if (this.isOutputFromDump(response)) {
    //                            this.onError(message);
    //                            this.showHtmlModal(response);
    //                        } else {
    //                            this.onMessage(
    //                                message,
    //                                JSON.parse(response)
    //                            );
    //                        }
    //                    });
    //                } else {
    //                    if (
    //                        this.onError(
    //                            message,
    //                            response.status,
    //                            response
    //                        ) === false
    //                    )
    //                        return;

    //                    if (response.status === 419) {
    //                        if (store.sessionHasExpired) return;

    //                        store.sessionHasExpired = true;

    //                        this.showExpiredMessage(
    //                            response,
    //                            message
    //                        );
    //                    } else {
    //                        response.text().then(response => {
    //                            this.showHtmlModal(response);
    //                        });
    //                    }
    //                }
    //            })
    //            .catch(() => {
    //                this.onError(message);
    //            });
    //    }
}
