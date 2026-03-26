export interface Uupg {
    id: string;
    slug: string;
    name: string;
    display_name: string;
    wagf_region: ValueLabel;
    wagf_block: ValueLabel;
    wagf_member: boolean;
    country: ValueLabel;
    rop1: ValueLabel;
    location_description: string;
    has_image: boolean;
    picture_url: string;
    picture_credit_html: string;
    population: number;
    religion: ValueLabel;
    adopted?: boolean;
    adopted_by_churches?: number;
    people_praying?: number;
    people_committed?: number;
    matches?: string;
}

interface ValueLabel {
    value: string;
    label: string;
    description?: string;
}