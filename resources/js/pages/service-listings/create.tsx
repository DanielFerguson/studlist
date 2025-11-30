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
import { ServiceListingForm } from '@/components/forms/service-listing-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'List Service',
        href: '/services/create',
    },
];

export default function ServiceCreate() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List Service" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>List Your Service</CardTitle>
                            <CardDescription>
                                Create a free listing for your cattle-related services. Services can be listed at no cost!
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ServiceListingForm />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}

