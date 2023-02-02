import Component from 'core/component';

export const eventDirectivePrefixRE = (): RegExp => /on|@/gim;
export const rawDirectiveSplitRE = (): RegExp => /:|\./gim;

export const hasDirectiveRE = (): RegExp => {
    return new RegExp(
        `(ui-|${Object.keys(DIRECTIVE_SHORTHANDS).join('|')})\\w+`,
        'gim'
    );
};

export const expressionPropRE = (prop: string): RegExp => {
    // Utilizes \b (word boundary) for prop differentiation.
    // Fails when next character is a \w (Word).
    return new RegExp(`\\b${prop}\\b`, 'gim');
};

export enum DIRECTIVE_SHORTHANDS {
    '@' = 'on',
    ':' = 'bind'
}

declare global {
    interface Window {
        leafUI: Component;
        _leafUIConfig: {
            el: HTMLElement;
            data: Record<string, any>;
            methods: string[];
            id: string;
            path: string;
            requestMethod: 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH' | 'HEAD';
        };
    }
}

window.leafUI = window.leafUI || {};
