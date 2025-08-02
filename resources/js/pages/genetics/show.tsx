import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Phone, Mail, MapPin, Calendar, Info, ExternalLink, Package } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';

interface GeneticsListing {
    id: number;
    name: string;
    price: number;
    photos?: string[];
    breed: string;
    type: string;
    sire?: string;
    dam?: string;
    registration_link?: string;
    storage_location: string;
    phone_contact?: string;
    email_contact?: string;
    description?: string;
    created_at: string;
    user: {
        name: string;
    };
}

interface Props {
    listing: GeneticsListing;
}

export default function GeneticsShow({ listing }: Props) {
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
                                <p className="text-2xl font-bold text-cyan-600 mt-2">${listing.price}</p>
                            </div>

                            {/* Quick Info */}
                            <div className="bg-white rounded-lg shadow p-6 space-y-4">
                                <h2 className="text-lg font-semibold mb-4">Details</h2>
                                
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <p className="text-sm text-gray-500">Type</p>
                                        <p className="font-medium">{listing.type}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Breed</p>
                                        <p className="font-medium">{listing.breed}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Storage Location</p>
                                        <p className="font-medium">{listing.storage_location}</p>
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
                                </div>

                                {listing.registration_link && (
                                    <div className="pt-4 border-t">
                                        <a 
                                            href={listing.registration_link} 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center text-cyan-600 hover:text-cyan-700"
                                        >
                                            <ExternalLink className="h-4 w-4 mr-1" />
                                            View Registration Details
                                        </a>
                                    </div>
                                )}

                                {listing.description && (
                                    <div className="pt-4 border-t">
                                        <p className="text-sm text-gray-500 mb-2">Description</p>
                                        <p className="text-gray-700 whitespace-pre-wrap">{listing.description}</p>
                                    </div>
                                )}
                            </div>

                            {/* Storage Info */}
                            <div className="bg-blue-50 rounded-lg p-4 flex items-start space-x-3">
                                <Package className="h-5 w-5 text-blue-600 mt-0.5" />
                                <div>
                                    <p className="font-medium text-blue-900">Storage Information</p>
                                    <p className="text-sm text-blue-700">
                                        Currently stored in {listing.storage_location}
                                    </p>
                                </div>
                            </div>

                            {/* Contact Information */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-semibold mb-4">Contact Seller</h2>
                                
                                <div className="space-y-3">
                                    <p className="font-medium">{listing.user.name}</p>
                                    
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
                                <p className="text-gray-700 mb-4">Interested in these genetics?</p>
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