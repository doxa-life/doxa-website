export interface Uupg {
    slug: string;
    name: string;
    wagf_region: KeyLabel;
    wagf_block: KeyLabel;
    wagf_member: boolean;
    country_code: KeyLabel;
    rop1: KeyLabel;
    location_description: string;
    has_image: boolean;
    image_url: string;
    picture_credit_html: string;
    population: number;
    religion: KeyLabel;
    adopted?: boolean;
    adopted_by_churches?: number;
    people_praying?: number;
    people_committed?: number;
}

interface KeyLabel {
    key: string;
    label: string;
}