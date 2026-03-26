import { LitElement, html } from 'lit';
import { repeat } from 'lit/directives/repeat.js';
import { property, customElement } from 'lit/decorators.js';
import Fuse from 'fuse.js/min-basic';
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
                        ${repeat(this.getUUPGsToDisplay(), (uupg: Uupg) => uupg.id, (uupg: Uupg) => {
                            if (this.useSelectCard) {
                                return html`
                                    <div class="stack stack--sm | card | highlighted-uupg__card">
                                        <div class="repel align-start">
                                            <img class="" src="${uupg.picture_url}" alt="${uupg.display_name}">
                                            <p class="color-brand-lighter uppercase text-end overflow-wrap-anywhere">${uupg.wagf_region_label ? uupg.wagf_region_label : uupg.wagf_region.label}</p>
                                        </div>
                                        <div>
                                            <p class="line-height-tight">${uupg.display_name}</p>
                                            ${uupg.matches ? html`
                                                ${uupg.matches.map(match => html`
                                                    <p class="font-size-sm color-brand-lighter"><strong>${match.key}</strong>: ${match.label}</p>
                                                `)}
                                            ` : ''}
                                        </div>
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
                                <img class="uupg__image" src="${uupg.picture_url}" alt="${uupg.display_name}">
                                <div class="uupg__header">
                                    <h3 class="uupg__name line-height-tight">${uupg.display_name}</h3>
                                    <p class="uupg__country">${uupg.country_label ? uupg.country_label : uupg.country.label} (${uupg.rop1_label ? uupg.rop1_label : uupg.rop1.label})</p>
                                    ${uupg.matches ? html`
                                        ${uupg.matches.map(match => html`
                                            <p class="font-size-sm color-brand-lighter"><strong>${match.key}</strong>: ${match.label}</p>
                                        `)}
                                    ` : ''}
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
        if (this.initialSearchTerm) {
            this.initialSearchTerm = decodeURI(this.initialSearchTerm);
            this.searchTerm = this.initialSearchTerm;
        }
        if (this.useHighlightedUUPGs) {
            this.getHighlightedUUPGs()
                .then(() => {
                    this.getUUPGs();
                });
        } else {
            this.getUUPGs();
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
        this.uupgs = this.uupgs.map(uupg => {
            uupg.matches = [];
            uupg.country_label = ''
            uupg.rop1_label = ''
            uupg.wagf_region_label = ''
            return uupg;
        });
        if (this.searchTerm === '') {
            this.filteredUUPGs = this.uupgs;
            this.total = this.filteredUUPGs.length;
            this.loading = false;
            return
        }
        this.dontShowListOnLoad = false;
        const options = {
            includeScore: true,
            includeMatches: true,
            ignoreLocation: true,
            minMatchCharLength: 3,
            threshold: 0.4,
            keys: [
                'display_name',
                'country.label',
                'rop1.label',
                'religion.label',
                'wagf_region.label',
                'wagf_block.label',
            ]
        }

        const fuse = new Fuse(this.uupgs, options)

        const result = fuse.search(this.searchTerm);
        this.filteredUUPGs = result.map(res => {
            // We need to not mutate the original item, so we create a new object
            const newItem = { ...res.item };
            if (!res.matches) {
                return newItem
            }
            (newItem as Uupg).matches = [];
            for (const match of res.matches) {
                const matchKey = match.key;
                if (!matchKey) {
                    continue;
                }
                const key = matchKey as keyof Uupg;
                let value = ''
                if (key.includes('.')) {
                    const [parentKey, childKey] = key.split('.');
                    value = (newItem as unknown as Record<string, Record<string,string>>)[parentKey][childKey];
                } else {
                    value = (newItem as unknown as Record<string, string>)[key];
                }
                if (value && typeof value === 'string') {
                    let currentIndex = 0;
                    let highlightedValue = html`
                        ${match.indices.map((index, i) => {
                            const start = index[0];
                            const end = index[1];
                            const isLastMatch = match.indices.length - 1 === i;
                            const highlight = html`${value.slice(currentIndex, start)}<span class="search-highlight">${value.slice(start, end + 1)}</span>${isLastMatch ? value.slice(end + 1) : ''}`;
                            currentIndex = end + 1;
                            return highlight;
                        })}
                    `;
                    if (key.includes('.')) {
                        const [parentKey] = key.split('.');
                        const keyTranslations = {
                            religion: this.t.religion,
                            country: this.t.country,
                            rop1: this.t.rop1,
                            wagf_region: this.t.wagf_region,
                            wagf_block: this.t.wagf_block,
                        };
                        if ('wagf_region' === parentKey && this.useSelectCard) {
                            newItem.wagf_region_label = highlightedValue;
                        } else if ('rop1' === parentKey && !this.useSelectCard) {
                            newItem.rop1_label = highlightedValue;
                        } else if ('country' === parentKey && !this.useSelectCard) {
                            newItem.country_label = highlightedValue;
                        } else if ('wagf_region' === parentKey && !this.useSelectCard) {
                            // do nothing
                        } else {
                            (newItem as Uupg).matches!.push({
                                key: keyTranslations[parentKey as keyof typeof keyTranslations],
                                label: highlightedValue,
                            });
                        }
                    } else {
                        (newItem as unknown as Record<string, unknown>)[key] = highlightedValue;
                    }
                }
            }

            return newItem
        });
        this.total = this.filteredUUPGs.length;
        this.loading = false;
    }

    getUUPGs() {
        const uupgAPIUrl = this.isDevelopment()
            ? 'http://uupg.doxa.test/wp-json/dt-public/disciple-tools-people-groups-api/v1/list'
            : 'https://pray.doxa.life/api/people-groups/list?lang=' + this.languageCode;

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
        const uupgAPIUrl = this.isDevelopment()
            ? 'http://uupg.doxa.test/wp-json/dt-public/disciple-tools-people-groups-api/v1/highlighted'
            : 'https://pray.doxa.life/api/people-groups/highlighted?lang=' + this.languageCode;

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

    isDevelopment() {
        return false;
        const url = new URL(window.location.href);
        return url.hostname !== 'doxa.life';
    }

    protected createRenderRoot(): HTMLElement | DocumentFragment {
        return this;
    }
}
