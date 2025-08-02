import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Phone, Mail } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';

interface SteerListing {
    id: number;
    name: string;
    photos?: string[];
    date_of_birth: string;
    breed: string;
    colour: string;
    location: string;
    sire?: string;
    dam?: string;
    business_contact?: string;
    phone_contact?: string;
    email_contact?: string;
    pic_number?: string;
    description?: string;
    started_on_feed: boolean;
    price?: number;
    created_at: string;
    user: {
        name: string;
    };
}

interface Props {
    listing: SteerListing;
}

export default function SteerShow({ listing }: Props) {
    return (
        <>
            <Head title={listing.name} />
            
            <PublicLayout>
                <div className="bg-gray-50 py-8">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Back Button */}
                    <Link href={route('search')} className="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to search
                    </Link>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Photo Gallery */}
                        <div className="space-y-4">
                            {listing.photos && listing.photos.length > 0 ? (
                                <>
                                    <div className="aspect-w-16 aspect-h-12 rounded-lg overflow-hidden bg-gray-200">
                                        <img 
                                            src={`/storage/${listing.photos[0]}`} 
                                            alt={listing.name}
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                    {listing.photos.length > 1 && (
                                        <div className="grid grid-cols-4 gap-2">
                                            {listing.photos.slice(1).map((photo, index) => (
                                                <div key={index} className="aspect-w-1 aspect-h-1 rounded overflow-hidden bg-gray-200">
                                                    <img 
                                                        src={`/storage/${photo}`} 
                                                        alt={`${listing.name} ${index + 2}`}
                                                        className="w-full h-full object-cover"
                                                    />
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </>
                            ) : (
                                <div className="aspect-w-16 aspect-h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <span className="text-gray-400">No photos available</span>
                                </div>
                            )}
                        </div>

                        {/* Listing Details */}
                        <div className="space-y-6">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">{listing.name}</h1>
                                {listing.price && (
                                    <p className="text-2xl font-bold text-cyan-600 mt-2">${listing.price}</p>
                                )}
                            </div>

                            {/* Quick Info */}
                            <div className="bg-white rounded-lg shadow p-6 space-y-4">
                                <h2 className="text-lg font-semibold mb-4">Details</h2>
                                
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <p className="text-sm text-gray-500">Breed</p>
                                        <p className="font-medium">{listing.breed}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Colour</p>
                                        <p className="font-medium">{listing.colour}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Date of Birth</p>
                                        <p className="font-medium">{new Date(listing.date_of_birth).toLocaleDateString()}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Location</p>
                                        <p className="font-medium">{listing.location}</p>
                                    </div>
                                    {listing.sire && (
                                        <div>
                                            <p className="text-sm text-gray-500">Sire</p>
                                            <p className="font-medium">{listing.sire}</p>
                                        </div>
                                    )}
                                    {listing.dam && (
                                        <div>
                                            <p className="text-sm text-gray-500">Dam</p>
                                            <p className="font-medium">{listing.dam}</p>
                                        </div>
                                    )}
                                    {listing.pic_number && (
                                        <div>
                                            <p className="text-sm text-gray-500">PIC Number</p>
                                            <p className="font-medium">{listing.pic_number}</p>
                                        </div>
                                    )}
                                    <div>
                                        <p className="text-sm text-gray-500">Started on Feed</p>
                                        <p className="font-medium">{listing.started_on_feed ? 'Yes' : 'No'}</p>
                                    </div>
                                </div>

                                {listing.description && (
                                    <div className="pt-4 border-t">
                                        <p className="text-sm text-gray-500 mb-2">Description</p>
                                        <p className="text-gray-700 whitespace-pre-wrap">{listing.description}</p>
                                    </div>
                                )}
                            </div>

                            {/* Contact Information */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-semibold mb-4">Contact Seller</h2>
                                
                                <div className="space-y-3">
                                    <p className="font-medium">{listing.user.name}</p>
                                    
                                    {listing.business_contact && (
                                        <p className="text-gray-700">{listing.business_contact}</p>
                                    )}
                                    
                                    {listing.phone_contact && (
                                        <a href={`tel:${listing.phone_contact}`} className="flex items-center text-cyan-600 hover:text-cyan-700">
                                            <Phone className="h-4 w-4 mr-2" />
                                            {listing.phone_contact}
                                        </a>
                                    )}
                                    
                                    {listing.email_contact && (
                                        <a href={`mailto:${listing.email_contact}`} className="flex items-center text-cyan-600 hover:text-cyan-700">
                                            <Mail className="h-4 w-4 mr-2" />
                                            {listing.email_contact}
                                        </a>
                                    )}
                                </div>
                            </div>

                            {/* Call to Action */}
                            <div className="bg-cyan-50 rounded-lg p-6 text-center">
                                <p className="text-gray-700 mb-4">Interested in this steer?</p>
                                <div className="space-x-4">
                                    {listing.phone_contact && (
                                        <Button asChild>
                                            <a href={`tel:${listing.phone_contact}`}>Call Seller</a>
                                        </Button>
                                    )}
                                    {listing.email_contact && (
                                        <Button variant="outline" asChild>
                                            <a href={`mailto:${listing.email_contact}`}>Email Seller</a>
                                        </Button>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </PublicLayout>
        </>
    );
}