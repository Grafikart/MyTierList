import { TierList } from '@/components/TierList';
import { createRoot, type Root } from 'react-dom/client';

class TierListElement extends HTMLElement {
    private root?: Root;

    constructor() {
        super();
    }

    connectedCallback() {
        this.root = createRoot(this);
        this.root.render(<TierList movies={JSON.parse(this.getAttribute('movies')!)} tiers={JSON.parse(this.getAttribute('tiers')!)} />);
    }

    disconnectedCallback() {
        this.root?.unmount();
    }

    handleClick() {
        alert('Element clicked!');
    }
}

customElements.define('tier-list', TierListElement);
