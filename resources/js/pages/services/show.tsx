import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Phone, Mail, MapPin, ExternalLink, Briefcase, Building } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import PublicLayout from '@/layouts/public-layout';

interface ServiceListing {
    id: number;
    type: string;
    abn?: string;
    business_name: string;
    contact_name: string;
    phone_contact?: string;
    email_contact?: string;
    locations_covered: string[];
    links?: string[];
    description?: string;
    created_at: string;
    user: {
        name: string;
    };
}

interface Props {
    listing: ServiceListing;
}

export default function ServiceShow({ listing }: Props) {
    return (
        <>
            <Head title={listing.business_name} />
            
            <PublicLayout>
                <div className="bg-gray-50 py-8">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Back Button */}
                    <Link href={route('search')} className="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
                        <ArrowLeft className="h-4 w-4 mr-1" />
                        Back to search
                    </Link>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Main Content */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Header */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <div className="flex items-start justify-between">
                                    <div>
                                        <Badge className="mb-2">{listing.type}</Badge>
                                        <h1 className="text-3xl font-bold text-gray-900">{listing.business_name}</h1>
                                        <p className="text-gray-600 mt-1">Listed by {listing.contact_name}</p>
                                    </div>
                                    <div className="hidden sm:block">
                                        <Briefcase className="h-12 w-12 text-cyan-600" />
                                    </div>
                                </div>
                            </div>

                            {/* Service Area */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-semibold mb-4 flex items-center">
                                    <MapPin className="h-5 w-5 mr-2 text-cyan-600" />
                                    Service Area
                                </h2>
                                <div className="flex flex-wrap gap-2">
                                    {listing.locations_covered.map((location) => (
                                        <Badge key={location} variant="outline" className="text-sm">
                                            {location}
                                        </Badge>
                                    ))}
                                </div>
                                <p className="text-sm text-gray-500 mt-3">
                                    This service provider covers {listing.locations_covered.length} {listing.locations_covered.length === 1 ? 'state/territory' : 'states/territories'} across Australia.
                                </p>
                            </div>

                            {/* Description */}
                            {listing.description && (
                                <div className="bg-white rounded-lg shadow p-6">
                                    <h2 className="text-lg font-semibold mb-4">About This Service</h2>
                                    <p className="text-gray-700 whitespace-pre-wrap">{listing.description}</p>
                                </div>
                            )}

                            {/* Links */}
                            {listing.links && listing.links.length > 0 && (
                                <div className="bg-white rounded-lg shadow p-6">
                                    <h2 className="text-lg font-semibold mb-4">Website & Social Links</h2>
                                    <div className="space-y-3">
                                        {listing.links.map((link, index) => (
                                            <a 
                                                key={index}
                                                href={link} 
                                                target="_blank" 
                                                rel="noopener noreferrer"
                                                className="flex items-center text-cyan-600 hover:text-cyan-700 break-all"
                                            >
                                                <ExternalLink className="h-4 w-4 mr-2 flex-shrink-0" />
                                                {link}
                                            </a>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            {/* Business Details */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-semibold mb-4 flex items-center">
                                    <Building className="h-5 w-5 mr-2 text-cyan-600" />
                                    Business Details
                                </h2>
                                
                                <div className="space-y-3">
                                    <div>
                                        <p className="text-sm text-gray-500">Business Name</p>
                                        <p className="font-medium">{listing.business_name}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Contact Person</p>
                                        <p className="font-medium">{listing.contact_name}</p>
                                    </div>
                                    {listing.abn && (
                                        <div>
                                            <p className="text-sm text-gray-500">ABN</p>
                                            <p className="font-medium">{listing.abn}</p>
                                        </div>
                                    )}
                                    <div>
                                        <p className="text-sm text-gray-500">Service Type</p>
                                        <p className="font-medium">{listing.type}</p>
                                    </div>
                                </div>
                            </div>

                            {/* Contact Information */}
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-lg font-semibold mb-4">Contact</h2>
                                
                                <div className="space-y-3">
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
                                <p className="text-gray-700 mb-4">Need this service?</p>
                                <div className="flex flex-col gap-3">
                                    {listing.phone_contact && (
                                        <Button asChild className="w-full">
                                            <a href={`tel:${listing.phone_contact}`}>Call Now</a>
                                        </Button>
                                    )}
                                    {listing.email_contact && (
                                        <Button variant="outline" asChild className="w-full">
                                            <a href={`mailto:${listing.email_contact}`}>Send Email</a>
                                        </Button>
                                    )}
                                </div>
                            </div>

                            {/* Free Service Badge */}
                            <div className="bg-green-50 rounded-lg p-4 text-center">
                                <p className="text-sm text-green-700 font-medium">
                                    Service listings are free on StudList!
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </PublicLayout>
        </>
    );
}

