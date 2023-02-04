/**
 * @author Caleb Porzio
 * @package livewire/livewire
 */
export function monkeyPatchDomSetAttributeToAllowAtSymbols() {
    // Because morphdom may add attributes to elements containing "@" symbols
    // like in the case of an Alpine `@click` directive, we have to patch
    // the standard Element.setAttribute method to allow this to work.
    let original = Element.prototype.setAttribute;

    let hostDiv = document.createElement('div');

    Element.prototype.setAttribute = function newSetAttribute(name, value) {
        if (!name.includes('@')) {
            return original.call(this, name, value);
        }

        hostDiv.innerHTML = `<span ${name}="${value}"></span>`;

        let attr = (hostDiv.firstElementChild)!.getAttributeNode(name)!;

        (hostDiv.firstElementChild)!.removeAttributeNode(attr);

        this.setAttributeNode(attr);
    };
}
