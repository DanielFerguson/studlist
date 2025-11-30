import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface SteerListing {
    id: number;
    user_id: number;
    name: string;
    photos: string[]; // Array of photo URLs or paths
    date_of_birth: string; // ISO date string
    breed: string;
    colour: string;
    location: string;
    sire?: string | null;
    dam?: string | null;
    contact_email?: string | null;
    contact_phone?: string | null;
    pic_number?: string | null;
    description?: string | null;
    started_on_feed: boolean;
    price?: number | null;
    status: 'draft' | 'active' | 'cancelled';
    stripe_subscription_id?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    business_contact?: string | null;
    phone_contact?: string | null;
    email_contact?: string | null;
}

export interface StudListing {
    id: number;
    user_id: number;
    name: string;
    photos: string[]; // Array of photo URLs or paths
    date_of_birth: string; // ISO date string
    breed: string;
    colour: string;
    tattoo_number?: string | null;
    location: string;
    sire?: string | null;
    dam?: string | null;
    registration_link?: string | null;
    business_contact?: string | null;
    phone_contact?: string | null;
    email_contact?: string | null;
    pic_number?: string | null;
    description?: string | null;
    status: 'draft' | 'active' | 'cancelled';
    stripe_subscription_id?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface GeneticsListing {
    id: number;
    user_id: number;
    name: string;
    price: number;
    photos: string[]; // Array of photo URLs or paths
    breed: string;
    type: 'Semen Straws' | 'Embryos';
    sire?: string | null;
    dam?: string | null;
    registration_link?: string | null;
    storage_location: 'ACT' | 'NSW' | 'NT' | 'QLD' | 'SA' | 'TAS' | 'VIC' | 'WA';
    phone_contact?: string | null;
    email_contact?: string | null;
    description?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface ShowEquipmentListing {
    id: number;
    user_id: number;
    title: string;
    description?: string | null;
    photos: string[]; // Array of photo URLs or paths
    condition: 'New' | 'Like New' | 'Good' | 'Fair' | 'Poor';
    location: string; // town, state
    phone_contact?: string | null;
    email_contact?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface ServiceListing {
    id: number;
    user_id: number;
    type: 'Photographer' | 'Fitter' | 'Feeder' | 'Other';
    abn?: string | null;
    business_name: string;
    contact_name: string;
    phone_contact?: string | null;
    email_contact?: string | null;
    locations_covered: ('ACT' | 'NSW' | 'NT' | 'QLD' | 'SA' | 'TAS' | 'VIC' | 'WA')[];
    links?: string[] | null;
    description?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}