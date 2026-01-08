export interface User {
    id: number;
    name: string;
    email: string;
}

export interface HayListing {
    id: number;
    user_id: number;
    title: string;
    hay_type: string | null;
    bale_type: string | null;
    quantity: number | null;
    weight_per_bale: number | null;
    season_cut: string | null;
    cut_year: number | null;
    quality_grade: string | null;
    test_results_available: boolean;
    protein_percentage: number | null;
    moisture_percentage: number | null;
    energy_mj_kg: number | null;
    nitrate_level: string | null;
    weather_damaged: boolean;
    storage_type: string | null;
    location: string | null;
    latitude: number | null;
    longitude: number | null;
    delivery_available: boolean;
    delivery_radius_km: number | null;
    minimum_order_quantity: number | null;
    price_type: string | null;
    price_per_bale: number | null;
    price_per_tonne: number | null;
    business_contact: string | null;
    phone_contact: string | null;
    email_contact: string | null;
    pic_number: string | null;
    photos: string[];
    description: string | null;
    display_price: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface HayFilters {
    hay_type?: string[];
    bale_type?: string[];
    quality_grade?: string[];
    location?: string;
    min_price?: number;
    max_price?: number;
    delivery_available?: boolean;
    test_results?: boolean;
    sort?: string;
}

export interface PaginatedListings {
    data: HayListing[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}
