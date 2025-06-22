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
import { GeneticsListingForm } from '@/components/forms/genetics-listing-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'List Genetics',
        href: '/genetics/create',
    },
];

export default function GeneticsCreate() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List Genetics" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>List Genetics</CardTitle>
                            <CardDescription>Create a new genetics listing for semen straws or embryos.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <GeneticsListingForm />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}