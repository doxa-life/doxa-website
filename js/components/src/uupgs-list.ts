import { LitElement, html, nothing } from 'lit';
import { repeat } from 'lit/directives/repeat.js';
import { property, customElement } from 'lit/decorators.js';
import Fuse from 'fuse.js';
import type { Uupg } from './types/uupg';
import type { FilterOption } from './filter-dropdown';

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

    @property({ type: Object, attribute: false })
    activeFilters: Record<string, { value: string; label: string }> = {};
    @property({ type: Boolean, attribute: false })
    filtersExpanded: boolean = false;
    @property({ type: Object, attribute: false })
    filterOptions: Record<string, FilterOption[]> = {};

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
                    <button
                        class="filters__toggle | button compact link"
                        type="button"
                        aria-expanded=${this.filtersExpanded}
                        @click=${this.toggleFilters}
                    >
                        ${this.filtersExpanded ? this.t.hide_filters || 'Hide Filters' : this.t.show_filters || 'Show Filters'}
                        <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    ${this.filtersExpanded ? html`
                        <div class="filters__panel">
                            <filter-dropdown
                                label="${this.t.wagf_region || 'WAGF Region'}"
                                name="wagf_region"
                                .options=${this.filterOptions.wagf_region || []}
                                value=${this.activeFilters.wagf_region?.value ?? ''}
                                placeholder="${this.t.type_to_search || 'Type to search...'}"
                                @filter-change=${this.onFilterChange}
                                @filter-clear=${this.onFilterClear}
                            ></filter-dropdown>
                            <filter-dropdown
                                label="${this.t.wagf_block || 'WAGF Block'}"
                                name="wagf_block"
                                .options=${this.filterOptions.wagf_block || []}
                                value=${this.activeFilters.wagf_block?.value ?? ''}
                                placeholder="${this.t.type_to_search || 'Type to search...'}"
                                @filter-change=${this.onFilterChange}
                                @filter-clear=${this.onFilterClear}
                            ></filter-dropdown>
                            <filter-dropdown
                                label="${this.t.country || 'Country'}"
                                name="country_code"
                                .options=${this.filterOptions.country_code || []}
                                value=${this.activeFilters.country_code?.value ?? ''}
                                placeholder="${this.t.type_to_search || 'Type to search...'}"
                                @filter-change=${this.onFilterChange}
                                @filter-clear=${this.onFilterClear}
                            ></filter-dropdown>
                            <filter-dropdown
                                label="${this.t.rop1 || 'People Group'}"
                                name="rop1"
                                .options=${this.filterOptions.rop1 || []}
                                value=${this.activeFilters.rop1?.value ?? ''}
                                placeholder="${this.t.type_to_search || 'Type to search...'}"
                                @filter-change=${this.onFilterChange}
                                @filter-clear=${this.onFilterClear}
                            ></filter-dropdown>
                            <filter-dropdown
                                label="${this.t.religion || 'Religion'}"
                                name="religion"
                                .options=${this.filterOptions.religion || []}
                                value=${this.activeFilters.religion?.value ?? ''}
                                placeholder="${this.t.type_to_search || 'Type to search...'}"
                                @filter-change=${this.onFilterChange}
                                @filter-clear=${this.onFilterClear}
                            ></filter-dropdown>
                        </div>
                        <div class="filters__panel">
                            <button
                                class="filter-toggle input fit-content"
                                type="button"
                                ?data-active=${!!this.activeFilters.adopted}
                                @click=${this.toggleAdopted}
                            >${this.t.adopted_filter || 'Adopted'}</button>
                            <button
                                class="filter-toggle input fit-content"
                                type="button"
                                ?data-active=${!!this.activeFilters.engaged}
                                @click=${this.toggleEngaged}
                            >${this.t.engaged_filter || 'Engaged'}</button>
                            <button
                                class="filter-toggle input fit-content"
                                type="button"
                                ?data-active=${!!this.activeFilters.exact}
                                @click=${this.toggleExact}
                            >
                                ${this.t.exact_filter || "Exact"}
                            </button>
                        </div>
                    ` : nothing}
                    ${Object.keys(this.activeFilters).length > 0 ? html`
                        <div class="filters__active">
                            ${Object.entries(this.activeFilters).map(([name, filter]) => html`
                                <span class="filter-chip">
                                    ${filter.label}
                                    <button class="filter-chip__remove" type="button" @click=${() => this.removeFilter(name)}>&times;</button>
                                </span>
                            `)}
                            <button class="filters__clear-all | button compact link" type="button" @click=${this.clearAllFilters}>${this.t.clear_all || 'Clear All'}</button>
                        </div>
                    ` : nothing}
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
                                            <p class="color-brand-lighter uppercase text-end overflow-wrap-anywhere">${uupg.wagf_region_label ? uupg.wagf_region_label : uupg.wagf_region.label}</p>
                                        </div>
                                        <div>
                                            <p class="line-height-tight">${uupg.name}</p>
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
                                <img class="uupg__image" src="${uupg.image_url}" alt="${uupg.name}">
                                <div class="uupg__header">
                                    <h3 class="uupg__name line-height-tight">${uupg.name}</h3>
                                    <p class="uupg__country">${uupg.country_label ? uupg.country_label : uupg.country_code.label} (${uupg.rop1_label ? uupg.rop1_label : uupg.rop1.label})</p>
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

    toggleFilters() {
        this.filtersExpanded = !this.filtersExpanded;
    }

    onFilterChange(e: CustomEvent) {
        const { name, value, label } = e.detail;
        this.activeFilters = { ...this.activeFilters, [name]: { value, label } };
        this.page = 1;
        this.filterUUPGs();
    }

    onFilterClear(e: CustomEvent) {
        this.removeFilter(e.detail.name);
    }

    removeFilter(name: string) {
        const newFilters = { ...this.activeFilters };
        delete newFilters[name];
        this.activeFilters = newFilters;
        this.page = 1;
        this.filterUUPGs();
    }

    clearAllFilters() {
        this.activeFilters = {};
        this.page = 1;
        this.filterUUPGs();
    }

    toggleAdopted() {
        if (this.activeFilters.adopted) {
            this.removeFilter('adopted');
        } else {
            this.activeFilters = { ...this.activeFilters, adopted: { value: 'yes', label: this.t.adopted_filter || 'Adopted' } };
            this.page = 1;
            this.filterUUPGs();
        }
    }

    toggleEngaged() {
        if (this.activeFilters.engaged) {
            this.removeFilter('engaged');
        } else {
            this.activeFilters = { ...this.activeFilters, engaged: { value: 'yes', label: this.t.engaged_filter || 'Engaged' } };
            this.page = 1;
            this.filterUUPGs();
        }
    }

    toggleExact() {
        if (this.activeFilters.exact) {
            this.removeFilter("exact");
        } else {
            this.activeFilters = {
                ...this.activeFilters,
                exact: { value: "yes", label: this.t.exact_filter || "Exact" },
            };
        this.page = 1;
            this.filterUUPGs();
        }
    }

    applyDropdownFilters(uupgs: Uupg[]): Uupg[] {
        let filtered = uupgs;
        for (const [field, selection] of Object.entries(this.activeFilters)) {
            if (field === 'adopted') {
                filtered = filtered.filter(u => (u.adopted_by_churches ?? 0) > 0);
            } else if (field === 'engaged') {
                filtered = filtered.filter(u => (u.people_praying ?? 0) > 0);
            } else if (field === "exact") {
                filtered = filtered;
            } else {
                filtered = filtered.filter(u => {
                    const vl = (u as unknown as Record<string, { value: string }>)[field];
                    return vl?.value === selection.value;
                });
            }
        }
        return filtered;
    }

    extractFilterOptions() {
        const extract = (field: string): FilterOption[] => {
            const counts = new Map<string, { label: string; count: number }>();
            for (const uupg of this.uupgs) {
                const vl = (uupg as unknown as Record<string, { value: string; label: string }>)[field];
                if (!vl?.value) continue;
                const existing = counts.get(vl.value);
                if (existing) existing.count++;
                else counts.set(vl.value, { label: vl.label, count: 1 });
            }
            return Array.from(counts.entries())
                .map(([value, { label, count }]) => ({ value, label, count }))
                .sort((a, b) => a.label.localeCompare(b.label));
        };
        this.filterOptions = {
            country_code: extract('country_code'),
            rop1: extract('rop1'),
            wagf_region: extract('wagf_region'),
            wagf_block: extract('wagf_block'),
            religion: extract('religion'),
        };
    }

    updateFilterOptionCounts() {
        const fields = Object.keys(this.filterOptions);
        const newFilterOptions: Record<string, FilterOption[]> = {};

        for (const field of fields) {
            // Apply all active filters EXCEPT this field's filter
            const filtersWithout: Record<string, { value: string; label: string }> = {};
            for (const [key, val] of Object.entries(this.activeFilters)) {
                if (key !== field) filtersWithout[key] = val;
            }
            const savedFilters = this.activeFilters;
            this.activeFilters = filtersWithout;
            const subset = Object.keys(filtersWithout).length > 0
                ? this.applyDropdownFilters(this.uupgs)
                : this.uupgs;
            this.activeFilters = savedFilters;

            // Count occurrences in the subset
            const counts = new Map<string, number>();
            for (const uupg of subset) {
                const vl = (uupg as unknown as Record<string, { value: string }>)[field];
                if (!vl?.value) continue;
                counts.set(vl.value, (counts.get(vl.value) ?? 0) + 1);
            }

            // Update counts on existing options, preserving labels and sort order
            newFilterOptions[field] = this.filterOptions[field].map(opt => ({
                ...opt,
                count: counts.get(opt.value) ?? 0,
            }));
        }

        this.filterOptions = newFilterOptions;
    }

    filterUUPGs() {
        this.uupgs = this.uupgs.map(uupg => {
            uupg.matches = [];
            uupg.country_label = ''
            uupg.rop1_label = ''
            uupg.wagf_region_label = ''
            return uupg;
        });

        // Apply dropdown filters first
        const hasActiveFilters = Object.keys(this.activeFilters).length > 0;
        let preFiltered = hasActiveFilters ? this.applyDropdownFilters(this.uupgs) : this.uupgs;

        if (hasActiveFilters) {
            this.dontShowListOnLoad = false;
        }

        if (this.searchTerm === '') {
            this.filteredUUPGs = preFiltered;
            this.total = this.filteredUUPGs.length;
            this.loading = false;
            this.updateFilterOptionCounts();
            return
        }
        this.dontShowListOnLoad = false;
        const options = {
            useTokenSearch: true,
            includeScore: true,
            includeMatches: true,
            ignoreLocation: true,
            minMatchCharLength: 3,
            threshold: !!this.activeFilters.exact ? 0 : 0.4,
            keys: [
                'name',
                'imb_alternate_name',
                'country_code.label',
                'rop1.label',
                'religion.label',
                'wagf_region.label',
                'wagf_block.label',
            ]
        }

        const fuse = new Fuse(preFiltered, options)

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
                    if (key.includes('imb_alternate_name')) {
                        (newItem as Uupg).matches!.push({
                            key: this.t.alternate_name,
                            label: highlightedValue,
                        });
                    } else if (key.includes('.')) {
                        const [parentKey] = key.split('.');
                        const keyTranslations = {
                            religion: this.t.religion,
                            country_code: this.t.country,
                            rop1: this.t.rop1,
                            wagf_region: this.t.wagf_region,
                            wagf_block: this.t.wagf_block,
                        };
                        if ('wagf_region' === parentKey && this.useSelectCard) {
                            newItem.wagf_region_label = highlightedValue;
                        } else if ('rop1' === parentKey && !this.useSelectCard) {
                            newItem.rop1_label = highlightedValue;
                        } else if ('country_code' === parentKey && !this.useSelectCard) {
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
        const prayBaseUrl = window.uupgsData?.prayBaseUrl || 'https://pray.doxa.life';
        const uupgAPIUrl = prayBaseUrl + '/api/people-groups/list?fields=name,slug,wagf_region,wagf_block,country_code,rop1,religion,has_photo,image_url,adopted_by_churches,imb_alternate_name,engagement_status&lang=' + this.languageCode;

        this.loading = true
        return fetch(uupgAPIUrl)
            .then(response => response.json())
            .then(data => {
                this.total = data.total;
                this.uupgs = data.posts;
                this.extractFilterOptions();
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
