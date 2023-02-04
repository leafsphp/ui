import lazy from './../utils/lazy';
import { renderDirective } from './../core/directives';
import { rawDirectiveSplitRE } from './../utils/data';
import { Directives, UINode, UINodeType } from './../@types/core';

const render = (
    uiNodes: UINode[],
    directives: Directives,
): void => {
    const legalDirectiveNames = Object.keys(directives);
    const LAZY_MODE_TIMEOUT = 25;

    lazy(LAZY_MODE_TIMEOUT, function*() {
        for (const node of uiNodes) {
            if (node.type === UINodeType.NULL) continue;
            const isStatic = node.type === UINodeType.STATIC;
            if (isStatic) node.type = UINodeType.NULL;
            yield;

            if (!isStatic) continue;

            for (const [directiveName, directiveData] of Object.entries(
                node.directives
            )) {
                const rawDirectiveName = directiveName.split(
                    rawDirectiveSplitRE()
                )[0];

                if (
                    !legalDirectiveNames.includes(
                        rawDirectiveName.toUpperCase()
                    )
                )
                    continue;
                yield;

                // If affected, then push to render queue
                if (isStatic) {
                    const directiveProps = {
                        el: node.el,
                        parts: directiveName.split(rawDirectiveSplitRE()),
                        data: directiveData,
                        node,
                    };

                    renderDirective(directiveProps, directives);

                    // [TODO] Remove this after testing
                    delete node.directives[directiveName];
                }
            }
        }
    })();
};

export default render;
