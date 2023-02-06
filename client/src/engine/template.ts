export default class template {
    static findAll(el: HTMLElement): HTMLElement[] {
        const elementsWithData = [];

        for (let i = 0; i < el.children.length; i++) {
            const child = el.children[i];
            const html = child.innerHTML;

            (child as HTMLElement).compile = () => template.compile(child as HTMLElement);

            if (/{{\s*\$(\w+)\s*}}/g.test(html)) {
                elementsWithData.push(child as HTMLElement);
            }
        }

        return elementsWithData;
    }

    static compile(element: HTMLElement): HTMLElement {
        const varToUpdate = element.textContent!.match(/{{\s*\$(\w+)\s*}}/g);
        element.textContent =
            window._leafUIConfig?.data?.[
                varToUpdate?.[0]?.replace(/{{\s*\$(\w+)\s*}}/g, '$1') ?? ''
            ];
        element.textContent = element.textContent!.replace(/{{\s*\$(\w+)\s*\|\s*(\w+)\s*}}/g, '${$2($1)}')!;

        return element;
    }
}
