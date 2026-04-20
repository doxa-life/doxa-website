export interface Uupg {
    slug: string;
    name: string;
    wagf_region: ValueLabel;
    wagf_region_label?: any;
    wagf_block: ValueLabel;
    wagf_member: ValueLabel;
    country_code: ValueLabel;
    country_label?: any;
    rop1: ValueLabel;
    rop1_label?: any;
    location_description: string;
    has_photo: boolean;
    image_url: string;
    picture_credit: any;
    population: number;
    religion: ValueLabel;
    adopted_by_churches?: number;
    people_praying?: number;
    people_committed?: number;
    matches?: Array<{
        key: string;
        label: any;
    }>;
}

interface ValueLabel {
    value: string;
    label: string;
    description?: string;
}