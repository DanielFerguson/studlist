import AppLayout from '@/layouts/app-layout';
import { StudListing, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { StudListingForm } from '@/components/forms/stud-listing-form';

interface Props {
    stud: StudListing;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/dashboard',
    },
    {
        title: 'Edit Stud',
        href: '#',
    },
];

export default function StudListingsEdit({ stud }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Stud Listing" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Edit Stud Listing</CardTitle>
                            <CardDescription>Update the details for your stud listing.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <StudListingForm stud={stud} />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
} 