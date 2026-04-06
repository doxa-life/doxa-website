import { LitElement, html } from 'lit';
import { repeat } from 'lit/directives/repeat.js';
import { property, customElement } from 'lit/decorators.js';
import type { Uupg } from './types/uupg';

@customElement('uupgs-list')
export class UupgsList extends LitElement {
    @property({ type: Object })
    t: Record<string, any> = {};
    @property({ type: String })
    selectUrl: string = '';
    @property({ type: String })
    researchUrl: string = '';
    @property({ type: String })
    initialSearchTerm: string = '';
    @property({ type: String })
    languageCode: string = '';

    @property({ type: Number })
    perPage: number = 24;
    @property({ type: Number })
    morePerPage: number = 0;
    @property({ type: Boolean })
    dontShowListOnLoad: boolean = false;
    @property({ type: Boolean })
    useSelectCard: boolean = false;
    @property({ type: Boolean })
    useHighlightedUUPGs: boolean = false;
    @property({ type: Boolean })
    randomizeList: boolean = false;
    @property({ type: Boolean })
    hideSeeAllLink: boolean = false;

    @property({ type: Array, attribute: false })
    uupgs: Uupg[] = [];
    @property({ type: Array, attribute: false })
    highlightedUUPGs: Uupg[] = [];
    @property({ type: Array, attribute: false })
    filteredUUPGs: Uupg[] = [];
    @property({ type: Number, attribute: false })
    total: number = 0;
    @property({ type: Number, attribute: false })
    page: number = 1;
    @property({ type: String, attribute: false })
    searchTerm: string = '';
    @property({ type: String, attribute: false })
    sort: string = '';
    @property({ type: Boolean, attribute: false })
    loading: boolean = true;
    @property({ type: Boolean, attribute: false })
    firstLoaded: boolean = true;

    constructor() {
        super();
        this.uupgs = [];
        this.highlightedUUPGs = [];
        this.researchUrl = '/research'
    }

    render() {
        return html`
            <div class="stack stack--md bg-image" style="background-image: url(${window.uupgsData.images_url}/worldmap.svg); background-size: 60%; background-position: top; min-height: 400px;">
                <div id="filters" class="filters">
                    <div class="search-box | center | max-width-md">
                        <span class="sr-only">${this.t.search}</span>
                        <svg class="search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <use href="/wp-content/themes/doxa-website/assets/icons/search.svg#search" />
                        </svg>
                        <input
                            type="search"
                            placeholder="${this.initialSearchTerm ? this.initialSearchTerm : this.t.search}"
                            @input=${this.debounce(this.onSearch, 500)}
                        />
                    </div>
                </div>
                <div class="stack stack--xs">
                    <div class="repel">
                        <div class="font-size-sm">
                            ${ !this.dontShowListOnLoad && !this.loading ? `
                                ${this.t.total}: ${this.total}
                            ` : html`<span class="invisible-placeholder">Placeholder</span>`}
                        </div>
                        ${
                            !this.hideSeeAllLink &&
                            !this.dontShowListOnLoad && this.hasMore() ? html`
                                <a class="light-link" href="${this.researchUrl + 'search/' + this.searchTerm}">${this.t.see_all}</a>
                            ` : ''
                        }
                    </div>
                    <div id="results" class="grid | uupgs-list ${this.useSelectCard ? 'gap-md' : ''}" ?data-width-lg=${!this.useSelectCard} ?data-width-md=${this.useSelectCard}>
                        ${repeat(this.getUUPGsToDisplay(), (uupg: Uupg) => uupg.slug, (uupg: Uupg) => {
                            if (this.useSelectCard) {
                                return html`
                                    <div class="stack stack--sm | card | highlighted-uupg__card">
                                        <div class="repel align-start">
                                            <img class="" src="${uupg.image_url}" alt="${uupg.name}">
                                            <p class="color-brand-lighter uppercase text-end overflow-wrap-anywhere">${uupg.wagf_region.label}</p>
                                        </div>
                                        <p class="">${uupg.name}</p>
                                        <div class="repel">
                                            <p class="font-size-sm color-brand-lighter">${this.t.prayer_coverage}:</p>
                                            <p class="font-size-xl font-button">${uupg.people_committed ?? 0}/144</p>
                                        </div>
                                        <div class="switcher | text-center" data-width="md">
                                            <a class="highlighted-uupg__prayer-coverage-button button compact" href="${this.selectUrl + uupg.slug + '?source=doxalife'}">${this.t.select}</a>
                                            <a class="highlighted-uupg__more-button button compact link" href="${this.researchUrl + uupg.slug}">${this.t.full_profile}</a>
                                        </div>
                                    </div>
                                `
                            }

                            const isAdopted = uupg.adopted_by_churches && uupg.adopted_by_churches > 0;
                            const adoptedBadgeImage = isAdopted
                                ? window.uupgsData.icons_url + '/Check-GreenCircle.png'
                                : window.uupgsData.icons_url + '/RedX-Circle.png';
                            const adoptedBadgeText = isAdopted ? this.t.adopted : this.t.not_adopted;

                            return html`<div class="card | uupg__card">
                                <img class="uupg__image" src="${uupg.image_url}" alt="${uupg.name}">
                                <div class="uupg__header">
                                    <h3 class="uupg__name">${uupg.name}</h3>
                                    <p class="uupg__country">${uupg.country_code.label} (${uupg.rop1.label})</p>
                                </div>
                                <div class="uupg_adopted">
                                    <div>
                                        <img src="${adoptedBadgeImage}" alt="${adoptedBadgeText}">
                                        <span>${adoptedBadgeText}</span>
                                    </div>
                                </div>
                                ${uupg.location_description ? html`
                                    <p class="uupg__content">${uupg.location_description}</p>
                                ` : ''}
                                <a class="uupg__more-button button compact" href="${this.researchUrl + uupg.slug}">${this.t.full_profile}</a>
                            </div>
                        `})}
                        ${!this.dontShowListOnLoad && this.loading ? html`<div class="loading">${this.t.loading}</div>` : ''}
                    </div>
                    ${this.hasMore() ? html`
                        <button
                            @click=${this.loadMore}
                            class="center | button compact stack-spacing-2xl"
                        >${this.t.load_more}</button>
                    ` : ''}
                </div>
            </div>
        `;
    }

    firstUpdated() {
        if (this.useHighlightedUUPGs) {
            this.getHighlightedUUPGs()
                .then(() => {
                    this.getUUPGs();
                });
        } else {
            this.getUUPGs();
        }

        if (this.initialSearchTerm) {
            this.searchTerm = this.initialSearchTerm;
        }
    }

    hasMore() {
        if (this.morePerPage > 0) {
            return this.total > this.perPage + ( this.page - 1 ) * this.morePerPage && !this.loading && this.filteredUUPGs.length > 0
        }
        return this.total > this.page * this.perPage && !this.loading && this.filteredUUPGs.length > 0
    }

    loadMore() {
        this.page = this.page + 1;
    }

    getUUPGsToDisplay() {
        if (this.morePerPage > 0) {
            return this.filteredUUPGs.slice(0, this.perPage + ( this.page - 1 ) * this.morePerPage)
        }
        return this.filteredUUPGs.slice(0, this.page * this.perPage)
    }

    debounce = (callback: (...args: any[]) => void, time = 500): ((...args: any[]) => void) => {
        let timeout: any;
        return (...args: any[]) => {
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(() => callback.apply(this, args as any), time);
        };
    };

    onSearch = (event: Event) => {
        this.searchTerm = (event.target as HTMLInputElement).value;
        this.search(this.searchTerm);
    }

    search(searchTerm: string) {
        this.loading = true;
        this.page = 1;
        this.total = 0;
        this.filteredUUPGs = [];
        this.filterUUPGs();
    }

    filterUUPGs() {
        this.dontShowListOnLoad = false;
        this.filteredUUPGs = this.uupgs.filter(uupg => {
            return uupg.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                uupg.country_code.label.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                uupg.rop1.label.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                uupg.religion.label.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                uupg.wagf_region.label.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                uupg.wagf_block.label.toLowerCase().includes(this.searchTerm.toLowerCase())
        });
        this.total = this.filteredUUPGs.length;
        this.loading = false;
    }

    getUUPGs() {
        const prayBaseUrl = window.uupgsData?.prayBaseUrl || 'https://pray.doxa.life';
        const uupgAPIUrl = prayBaseUrl + '/api/people-groups/list?lang=' + this.languageCode;

        this.loading = true
        return fetch(uupgAPIUrl)
            .then(response => response.json())
            .then(data => {
                this.total = data.total;
                this.uupgs = data.posts;
                if (this.randomizeList) {
                    this.uupgs = this.uupgs.sort(() => Math.random() - 0.5);
                }
                if (this.useHighlightedUUPGs) {
                    this.filteredUUPGs = [
                        ...this.filteredUUPGs,
                        ...data.posts,
                    ]
                } {
                }
                if (!this.dontShowListOnLoad && !this.useHighlightedUUPGs) {
                    this.filterUUPGs();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.loading = false;
            });
    }

    getHighlightedUUPGs() {
        const prayBaseUrl = window.uupgsData?.prayBaseUrl || 'https://pray.doxa.life';
        const uupgAPIUrl = prayBaseUrl + '/api/people-groups/highlighted?lang=' + this.languageCode;

        const url = new URL(uupgAPIUrl);

        return fetch(url.href)
            .then(response => response.json())
            .then(data => {
                this.highlightedUUPGs = data.posts;
                this.total = data.total;
                this.filteredUUPGs = this.highlightedUUPGs;
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.loading = false;
            });
    }

    protected createRenderRoot(): HTMLElement | DocumentFragment {
        return this;
    }
}
