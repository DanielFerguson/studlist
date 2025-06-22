import AppLayout from '@/layouts/app-layout';
import { GeneticsListing, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { GeneticsListingForm } from '@/components/forms/genetics-listing-form';

interface Props {
    genetics: GeneticsListing;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Edit Genetics',
        href: '#',
    },
];

export default function GeneticsEdit({ genetics }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Genetics Listing" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Edit Genetics Listing</CardTitle>
                            <CardDescription>Update the details for your genetics listing.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <GeneticsListingForm genetics={genetics} />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}