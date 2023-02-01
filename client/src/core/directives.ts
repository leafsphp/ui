import { DirectiveProps, Directives } from './../@types/core';
// import { bindDirective } from './directives/bind';
// import { modelDirective } from './directives/model';
import { onDirective } from './directives/on';

export const directives: Directives = {
    // BIND: bindDirective,
    // MODEL: modelDirective,
    ON: onDirective,
};

export const renderDirective = (
    props: DirectiveProps,
    directives: Directives
): void => {
    directives[props.parts[0].toUpperCase()](props);
};
