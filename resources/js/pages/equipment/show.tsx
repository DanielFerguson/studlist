import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Phone, Mail, MapPin, Calendar, Info, Package2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import PublicLayout from '@/layouts/public-layout';

interface EquipmentListing {
    id: number;
    title: string;
    description?: string;
    photos?: string[];
    condition: string;
    location: string;
    phone_contact?: string;
    email_contact?: string;
    created_at: string;
    user: {
        name: string;
    };
}

interface Props {
    listing: EquipmentListing;
}

export default function EquipmentShow({ listing }: Props) {
    const getConditionColor = (condition: string) => {
        switch (condition) {
            case 'New':
                return 'bg-green-100 text-green-800';
            case 'Like New':
                return 'bg-blue-100 text-blue-800';
            case 'Good':
                return 'bg-yellow-100 text-yellow-800';
            case 'Fair':
                return 'bg-orange-100 text-orange-800';
            case 'Poor':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <>
            <Head title={listing.title} />
            
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
                                            alt={listing.title}
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                    {listing.photos.length > 1 && (
                                        <div className="grid grid-cols-4 gap-2">
                                            {listing.photos.slice(1).map((photo, index) => (
                                                <div key={index} className="aspect-w-1 aspect-h-1 rounded overflow-hidden bg-gray-200">
                                                    <img 
                                                        src={`/storage/${photo}`} 
                                                        alt={`${listing.title} ${index + 2}`}
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
                                <h1 className="text-3xl font-bold text-gray-900">{listing.title}</h1>
                                <div className="flex items-center mt-3 space-x-4">
                                    <Badge className={getConditionColor(listing.condition)}>
                                        {listing.condition}
                                    </Badge>
                                    <span className="text-gray-600">Show Equipment</span>
                                </div>
                            </div>

                            {/* Quick Info */}
                            <div className="bg-white rounded-lg shadow p-6 space-y-4">
                                <h2 className="text-lg font-semibold mb-4">Details</h2>
                                
                                <div className="space-y-3">
                                    <div>
                                        <p className="text-sm text-gray-500">Condition</p>
                                        <p className="font-medium">{listing.condition}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Location</p>
                                        <p className="font-medium">{listing.location}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Listed</p>
                                        <p className="font-medium">{new Date(listing.created_at).toLocaleDateString()}</p>
                                    </div>
                                </div>

                                {listing.description && (
                                    <div className="pt-4 border-t">
                                        <p className="text-sm text-gray-500 mb-2">Description</p>
                                        <p className="text-gray-700 whitespace-pre-wrap">{listing.description}</p>
                                    </div>
                                )}
                            </div>

                            {/* Equipment Info */}
                            <div className="bg-yellow-50 rounded-lg p-4 flex items-start space-x-3">
                                <Package2 className="h-5 w-5 text-yellow-600 mt-0.5" />
                                <div>
                                    <p className="font-medium text-yellow-900">Equipment Listing</p>
                                    <p className="text-sm text-yellow-700">
                                        This is professional show equipment in {listing.condition.toLowerCase()} condition
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
                                <p className="text-gray-700 mb-4">Interested in this equipment?</p>
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