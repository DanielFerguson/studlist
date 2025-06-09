import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { SteerListingForm } from '@/components/forms/steer-listing-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/listings',
    },
];

export default function ListingsCreate() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List a Steer" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>List a Steer</CardTitle>
                            <CardDescription>Create a new listing for your property.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <SteerListingForm />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
