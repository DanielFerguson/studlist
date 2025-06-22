import AppLayout from '@/layouts/app-layout';
import { SteerListing, StudListing, GeneticsListing, ShowEquipmentListing, type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"
import { Button } from '@/components/ui/button';
import { PencilIcon, PlusIcon, TrashIcon, CreditCardIcon, XIcon } from 'lucide-react';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog"
import { Badge } from '@/components/ui/badge';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    steerListings: SteerListing[];
    studListings: StudListing[];
    geneticsListings: GeneticsListing[];
    showEquipmentListings: ShowEquipmentListing[];
}

function getStatusBadge(status: SteerListing['status'] | StudListing['status'] | GeneticsListing['status']) {
    switch (status) {
        case 'active':
            return <Badge variant="default" className="bg-green-500">Active</Badge>;
        case 'draft':
            return <Badge variant="secondary">Draft</Badge>;
        case 'cancelled':
            return <Badge variant="destructive">Cancelled</Badge>;
        default:
            return <Badge variant="secondary">Unknown</Badge>;
    }
}

export default function Dashboard({ steerListings, studListings, geneticsListings, showEquipmentListings }: DashboardProps) {
    const { flash } = usePage().props as { flash?: { success?: string; error?: string } };

    const [selectedSteerIdDelete, setSelectedSteerIdDelete] = useState<number | null>(null);
    const [selectedStudIdDelete, setSelectedStudIdDelete] = useState<number | null>(null);
    const [selectedGeneticsIdDelete, setSelectedGeneticsIdDelete] = useState<number | null>(null);
    const [selectedShowEquipmentIdDelete, setSelectedShowEquipmentIdDelete] = useState<number | null>(null);

    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }
        if (flash?.error) {
            toast.error(flash.error);
        }
    }, [flash]);

    const handleDeleteSteer = (steerId: number) => {
        router.delete(`/steers/${steerId}`, {
            onSuccess: () => {
                setSelectedSteerIdDelete(null);
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                setSelectedSteerIdDelete(null);
            },
            onFinish: () => {
                // This runs regardless of success or error
                setSelectedSteerIdDelete(null);
            }
        });
    }

    const handleDeleteStud = (studId: number) => {
        router.delete(`/studs/${studId}`, {
            onSuccess: () => {
                setSelectedStudIdDelete(null);
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                setSelectedStudIdDelete(null);
            },
            onFinish: () => {
                // This runs regardless of success or error
                setSelectedStudIdDelete(null);
            }
        });
    }

    const handleDeleteGenetics = (geneticsId: number) => {
        router.delete(`/genetics/${geneticsId}`, {
            onSuccess: () => {
                setSelectedGeneticsIdDelete(null);
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                setSelectedGeneticsIdDelete(null);
            },
            onFinish: () => {
                // This runs regardless of success or error
                setSelectedGeneticsIdDelete(null);
            }
        });
    }

    const handleDeleteShowEquipment = (showEquipmentId: number) => {
        router.delete(`/show-equipment/${showEquipmentId}`, {
            onSuccess: () => {
                setSelectedShowEquipmentIdDelete(null);
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                setSelectedShowEquipmentIdDelete(null);
            },
            onFinish: () => {
                // This runs regardless of success or error
                setSelectedShowEquipmentIdDelete(null);
            }
        });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                {/* Steer Listings table */}
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Steers</CardTitle>
                            <CardDescription>
                                These are your steer listings. Draft listings are not visible to the public until you subscribe for $15/month.
                            </CardDescription>
                            <CardAction className="flex gap-2">
                                <Link href={route('steers.create')}>
                                    <Button size="sm">
                                        <PlusIcon />
                                        List Steer
                                    </Button>
                                </Link>
                            </CardAction>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Price</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {steerListings.map((item) => (
                                        <TableRow key={item.id}>
                                            <TableCell className="font-medium">
                                                <Link href={route('steers.edit', item.id)} className='hover:underline'>
                                                    {item.name}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                {getStatusBadge(item.status)}
                                            </TableCell>
                                            <TableCell>
                                                {item.price ? `$${item.price.toLocaleString()}` : 'Not specified'}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex justify-end gap-2 flex-wrap">
                                                    {/* Subscription Management Buttons */}
                                                    {item.status === 'draft' && (
                                                        <Button
                                                            size="sm"
                                                            onClick={() => router.post(`/subscriptions/checkout/${item.id}`)}
                                                        >
                                                            <CreditCardIcon />
                                                            Subscribe ($15/mo)
                                                        </Button>
                                                    )}

                                                    {(item.status === 'active') && (
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => router.post(`/subscriptions/cancel/${item.id}`)}
                                                        >
                                                            <XIcon />
                                                            Cancel Subscription
                                                        </Button>
                                                    )}

                                                    {item.status === 'cancelled' && (
                                                        <Button
                                                            size="sm"
                                                            onClick={() => router.post(`/subscriptions/checkout/${item.id}`)}
                                                        >
                                                            <CreditCardIcon />
                                                            Reactivate ($15/mo)
                                                        </Button>
                                                    )}

                                                    <Link href={route('steers.edit', item.id)}>
                                                        <Button size="sm" variant="outline">
                                                            <PencilIcon />
                                                            Edit
                                                        </Button>
                                                    </Link>

                                                    <Button
                                                        size="sm"
                                                        variant="destructive"
                                                        onClick={() => setSelectedSteerIdDelete(item.id)}
                                                    >
                                                        <TrashIcon />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                                {steerListings.length === 0 && (
                                    <TableCaption>
                                        No steer listings found.
                                    </TableCaption>
                                )}
                            </Table>
                        </CardContent>
                    </Card>
                </div>

                {/* Stud Listings table */}
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Studs</CardTitle>
                            <CardDescription>
                                These are your stud listings. Draft listings are not visible to the public until you subscribe for $15/month.
                            </CardDescription>
                            <CardAction className="flex gap-2">
                                <Link href={route('studs.create')}>
                                    <Button size="sm">
                                        <PlusIcon />
                                        List Stud
                                    </Button>
                                </Link>
                            </CardAction>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Tattoo</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {studListings.map((item) => (
                                        <TableRow key={item.id}>
                                            <TableCell className="font-medium">
                                                <Link href={route('studs.edit', item.id)} className='hover:underline'>
                                                    {item.name}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                {getStatusBadge(item.status)}
                                            </TableCell>
                                            <TableCell>
                                                {item.tattoo_number || 'Not specified'}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex justify-end gap-2 flex-wrap">
                                                    {/* Subscription Management Buttons */}
                                                    {item.status === 'draft' && (
                                                        <Button
                                                            size="sm"
                                                            onClick={() => router.post(`/subscriptions/checkout-stud/${item.id}`)}
                                                        >
                                                            <CreditCardIcon />
                                                            Subscribe ($15/mo)
                                                        </Button>
                                                    )}

                                                    {(item.status === 'active') && (
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() => router.post(`/subscriptions/cancel-stud/${item.id}`)}
                                                        >
                                                            <XIcon />
                                                            Cancel Subscription
                                                        </Button>
                                                    )}

                                                    {item.status === 'cancelled' && (
                                                        <Button
                                                            size="sm"
                                                            onClick={() => router.post(`/subscriptions/checkout-stud/${item.id}`)}
                                                        >
                                                            <CreditCardIcon />
                                                            Reactivate ($15/mo)
                                                        </Button>
                                                    )}

                                                    <Link href={route('studs.edit', item.id)}>
                                                        <Button size="sm" variant="outline">
                                                            <PencilIcon />
                                                            Edit
                                                        </Button>
                                                    </Link>

                                                    <Button
                                                        size="sm"
                                                        variant="destructive"
                                                        onClick={() => setSelectedStudIdDelete(item.id)}
                                                    >
                                                        <TrashIcon />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                                {studListings.length === 0 && (
                                    <TableCaption>
                                        No stud listings found.
                                    </TableCaption>
                                )}
                            </Table>
                        </CardContent>
                    </Card>
                </div>

                {/* Genetics Listings table */}
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Genetics</CardTitle>
                            <CardDescription>
                                These are your genetics listings. Genetics listings are free to post.
                            </CardDescription>
                            <CardAction className="flex gap-2">
                                <Link href={route('genetics.create')}>
                                    <Button size="sm">
                                        <PlusIcon />
                                        List Genetics
                                    </Button>
                                </Link>
                            </CardAction>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Type</TableHead>
                                        <TableHead>Price</TableHead>
                                        <TableHead>Location</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {geneticsListings.map((item) => (
                                        <TableRow key={item.id}>
                                            <TableCell className="font-medium">
                                                <Link href={route('genetics.edit', item.id)} className='hover:underline'>
                                                    {item.name}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                {item.type}
                                            </TableCell>
                                            <TableCell>
                                                ${item.price.toLocaleString()}
                                            </TableCell>
                                            <TableCell>
                                                {item.storage_location}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex justify-end gap-2 flex-wrap">
                                                    <Link href={route('genetics.edit', item.id)}>
                                                        <Button size="sm" variant="outline">
                                                            <PencilIcon />
                                                            Edit
                                                        </Button>
                                                    </Link>

                                                    <Button
                                                        size="sm"
                                                        variant="destructive"
                                                        onClick={() => setSelectedGeneticsIdDelete(item.id)}
                                                    >
                                                        <TrashIcon />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                                {geneticsListings.length === 0 && (
                                    <TableCaption>
                                        No genetics listings found.
                                    </TableCaption>
                                )}
                            </Table>
                        </CardContent>
                    </Card>
                </div>

                {/* Show Equipment Listings table */}
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Show Equipment</CardTitle>
                            <CardDescription>
                                These are your show equipment listings. Show equipment listings are free to post.
                            </CardDescription>
                            <CardAction className="flex gap-2">
                                <Link href={route('show-equipment.create')}>
                                    <Button size="sm">
                                        <PlusIcon />
                                        List Show Equipment
                                    </Button>
                                </Link>
                            </CardAction>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Title</TableHead>
                                        <TableHead>Condition</TableHead>
                                        <TableHead>Location</TableHead>
                                        <TableHead className="text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {showEquipmentListings.map((item) => (
                                        <TableRow key={item.id}>
                                            <TableCell className="font-medium">
                                                <Link href={route('show-equipment.edit', item.id)} className='hover:underline'>
                                                    {item.title}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                {item.condition}
                                            </TableCell>
                                            <TableCell>
                                                {item.location}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <div className="flex justify-end gap-2 flex-wrap">
                                                    <Link href={route('show-equipment.edit', item.id)}>
                                                        <Button size="sm" variant="outline">
                                                            <PencilIcon />
                                                            Edit
                                                        </Button>
                                                    </Link>

                                                    <Button
                                                        size="sm"
                                                        variant="destructive"
                                                        onClick={() => setSelectedShowEquipmentIdDelete(item.id)}
                                                    >
                                                        <TrashIcon />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                                {showEquipmentListings.length === 0 && (
                                    <TableCaption>
                                        No show equipment listings found.
                                    </TableCaption>
                                )}
                            </Table>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <Dialog open={selectedSteerIdDelete !== null} onOpenChange={() => setSelectedSteerIdDelete(null)}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone. This will permanently delete the steer listing
                            and remove it from our servers. It will also remove it from the public listings, and cancel any subscriptions.
                        </DialogDescription>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setSelectedSteerIdDelete(null)}>Cancel</Button>
                            <Button variant="destructive" onClick={() => handleDeleteSteer(selectedSteerIdDelete!)}>Delete</Button>
                        </DialogFooter>
                    </DialogHeader>
                </DialogContent>
            </Dialog>

            <Dialog open={selectedStudIdDelete !== null} onOpenChange={() => setSelectedStudIdDelete(null)}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone. This will permanently delete the stud listing
                            and remove it from our servers. It will also remove it from the public listings, and cancel any subscriptions.
                        </DialogDescription>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setSelectedStudIdDelete(null)}>Cancel</Button>
                            <Button variant="destructive" onClick={() => handleDeleteStud(selectedStudIdDelete!)}>Delete</Button>
                        </DialogFooter>
                    </DialogHeader>
                </DialogContent>
            </Dialog>

            <Dialog open={selectedGeneticsIdDelete !== null} onOpenChange={() => setSelectedGeneticsIdDelete(null)}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone. This will permanently delete the genetics listing
                            and remove it from our servers. It will also remove it from the public listings, and cancel any subscriptions.
                        </DialogDescription>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setSelectedGeneticsIdDelete(null)}>Cancel</Button>
                            <Button variant="destructive" onClick={() => handleDeleteGenetics(selectedGeneticsIdDelete!)}>Delete</Button>
                        </DialogFooter>
                    </DialogHeader>
                </DialogContent>
            </Dialog>

            <Dialog open={selectedShowEquipmentIdDelete !== null} onOpenChange={() => setSelectedShowEquipmentIdDelete(null)}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone. This will permanently delete the show equipment listing
                            and remove it from our servers. It will also remove it from the public listings.
                        </DialogDescription>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setSelectedShowEquipmentIdDelete(null)}>Cancel</Button>
                            <Button variant="destructive" onClick={() => handleDeleteShowEquipment(selectedShowEquipmentIdDelete!)}>Delete</Button>
                        </DialogFooter>
                    </DialogHeader>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
