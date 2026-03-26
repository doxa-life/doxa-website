import { LitElement, html, nothing } from 'lit';
import { property, state, customElement } from 'lit/decorators.js';

export interface FilterOption {
    value: string;
    label: string;
    count: number;
    type?: 'dropdown' | 'boolean';
}

@customElement('filter-dropdown')
export class FilterDropdown extends LitElement {
    @property({ type: String })
    label: string = '';

    @property({ type: String })
    name: string = '';

    @property({ type: Array })
    options: FilterOption[] = [];

    @property({ type: String })
    value: string = '';

    @property({ type: String })
    placeholder: string = 'Type to search...';

    @state()
    private isOpen: boolean = false;

    @state()
    private searchText: string = '';

    @state()
    private highlightedIndex: number = -1;

    private boundOnClickOutside = this.onClickOutside.bind(this);

    render() {
        const filteredOptions = this.getFilteredOptions();
        const selectedOption = this.options.find(o => o.value === this.value);

        return html`
            <div class="filter-dropdown ${this.isOpen ? 'is-open' : ''}">
                <button
                    class="filter-dropdown__trigger input"
                    ?data-active=${!!this.value}
                    @click=${this.toggle}
                    type="button"
                    aria-haspopup="listbox"
                    aria-expanded=${this.isOpen}
                >
                    <span class="filter-dropdown__label">
                        ${selectedOption ? html`${this.label}: <strong>${selectedOption.label}</strong>` : this.label}
                    </span>
                    <svg class="filter-dropdown__chevron" width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                ${this.isOpen ? html`
                    <div class="filter-dropdown__menu" role="listbox">
                        <input
                            class="filter-dropdown__search"
                            type="text"
                            .value=${this.searchText}
                            placeholder=${this.placeholder}
                            @input=${this.onSearchInput}
                            @keydown=${this.onKeydown}
                        />
                        ${this.value ? html`
                            <button
                                class="filter-dropdown__clear"
                                type="button"
                                @click=${this.clear}
                            >Clear selection</button>
                        ` : nothing}
                        <div class="filter-dropdown__options">
                            ${filteredOptions.length === 0 ? html`
                                <div class="filter-dropdown__no-options">No options found</div>
                            ` : filteredOptions.map((option, index) => html`
                                <div
                                    class="filter-dropdown__option"
                                    role="option"
                                    ?data-highlighted=${index === this.highlightedIndex}
                                    ?data-selected=${option.value === this.value}
                                    aria-selected=${option.value === this.value}
                                    @click=${() => this.select(option)}
                                    @mouseenter=${() => this.highlightedIndex = index}
                                >
                                    <span>${option.label}</span>
                                    <span class="filter-dropdown__count">${option.count}</span>
                                </div>
                            `)}
                        </div>
                    </div>
                ` : nothing}
            </div>
        `;
    }

    private getFilteredOptions(): FilterOption[] {
        if (!this.searchText) return this.options;
        const search = this.searchText.toLowerCase();
        return this.options.filter(o => o.label.toLowerCase().includes(search));
    }

    private toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    private open() {
        this.isOpen = true;
        this.searchText = '';
        this.highlightedIndex = -1;
        document.addEventListener('click', this.boundOnClickOutside);
        this.updateComplete.then(() => {
            const input = this.querySelector('.filter-dropdown__search') as HTMLInputElement;
            input?.focus();
        });
    }

    private close() {
        this.isOpen = false;
        this.searchText = '';
        this.highlightedIndex = -1;
        document.removeEventListener('click', this.boundOnClickOutside);
    }

    private onClickOutside(e: Event) {
        const path = e.composedPath();
        if (!path.includes(this)) {
            this.close();
        }
    }

    private onSearchInput(e: Event) {
        this.searchText = (e.target as HTMLInputElement).value;
        this.highlightedIndex = -1;
    }

    private onKeydown(e: KeyboardEvent) {
        const options = this.getFilteredOptions();
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.highlightedIndex = Math.min(this.highlightedIndex + 1, options.length - 1);
                this.scrollHighlightedIntoView();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                this.scrollHighlightedIntoView();
                break;
            case 'Enter':
                e.preventDefault();
                if (this.highlightedIndex >= 0 && this.highlightedIndex < options.length) {
                    this.select(options[this.highlightedIndex]);
                }
                break;
            case 'Escape':
                e.preventDefault();
                this.close();
                break;
        }
    }

    private scrollHighlightedIntoView() {
        this.updateComplete.then(() => {
            const highlighted = this.querySelector('.filter-dropdown__option[data-highlighted]');
            highlighted?.scrollIntoView({ block: 'nearest' });
        });
    }

    private select(option: FilterOption) {
        this.dispatchEvent(new CustomEvent('filter-change', {
            detail: { name: this.name, value: option.value, label: option.label, type: option.type },
            bubbles: true,
            composed: true,
        }));
        this.close();
    }

    private clear() {
        this.dispatchEvent(new CustomEvent('filter-clear', {
            detail: { name: this.name },
            bubbles: true,
            composed: true,
        }));
        this.close();
    }

    disconnectedCallback() {
        super.disconnectedCallback();
        document.removeEventListener('click', this.boundOnClickOutside);
    }

    protected createRenderRoot(): HTMLElement | DocumentFragment {
        return this;
    }
}
