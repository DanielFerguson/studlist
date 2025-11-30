import { z } from "zod";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { router } from '@inertiajs/react';
import { useState, useEffect } from 'react';

import { Button } from "@/components/ui/button";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { Checkbox } from "@/components/ui/checkbox";
import { ServiceListing } from "@/types";
import { PlusIcon, TrashIcon } from "lucide-react";

// Predefined lists
const serviceTypes = [
    { value: "Photographer", label: "Photographer" },
    { value: "Fitter", label: "Fitter" },
    { value: "Feeder", label: "Feeder" },
    { value: "Other", label: "Other" },
];

const australianStates = [
    { value: "ACT", label: "Australian Capital Territory" },
    { value: "NSW", label: "New South Wales" },
    { value: "NT", label: "Northern Territory" },
    { value: "QLD", label: "Queensland" },
    { value: "SA", label: "South Australia" },
    { value: "TAS", label: "Tasmania" },
    { value: "VIC", label: "Victoria" },
    { value: "WA", label: "Western Australia" },
];

// Zod schema for form validation  
const formSchema = z.object({
    type: z.string().min(1, { message: "Please select a service type." }),
    abn: z.string().max(14, { message: "ABN must not exceed 14 characters." }).optional().or(z.literal("")),
    businessName: z.string().min(2, { message: "Business name must be at least 2 characters." }),
    contactName: z.string().min(2, { message: "Contact name must be at least 2 characters." }),
    phoneContact: z.string().optional(),
    emailContact: z.string().email("Invalid email address.").optional().or(z.literal("")),
    locationsCovered: z.array(z.string()).min(1, { message: "Please select at least one location." }),
    links: z.array(z.string().url("Invalid URL.").or(z.literal(""))).max(5, { message: "Maximum 5 links allowed." }).optional(),
    description: z.string().max(1000, {
        message: "Description must not exceed 1000 characters.",
    }).optional(),
}).refine(
    (data) => data.phoneContact || data.emailContact,
    {
        message: "Either Phone or Email contact is required.",
        path: ["phoneContact"],
    }
);

// Infer type from schema
type ServiceListingFormValues = z.infer<typeof formSchema>;

interface ServiceListingFormProps {
    service?: ServiceListing;
}

export function ServiceListingForm({ service }: ServiceListingFormProps) {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [links, setLinks] = useState<string[]>(service?.links || ['']);
    const isEditing = !!service;

    const form = useForm<ServiceListingFormValues>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            type: service?.type || "",
            abn: service?.abn || "",
            businessName: service?.business_name || "",
            contactName: service?.contact_name || "",
            phoneContact: service?.phone_contact || "",
            emailContact: service?.email_contact || "",
            locationsCovered: service?.locations_covered || [],
            links: service?.links || [''],
            description: service?.description || "",
        },
    });

    // Set select fields separately due to controlled component requirements
    useEffect(() => {
        if (service?.type) {
            form.setValue('type', service.type);
        }
        if (service?.locations_covered) {
            form.setValue('locationsCovered', service.locations_covered);
        }
    }, [service, form]);

    // Sync links state with form
    useEffect(() => {
        form.setValue('links', links);
    }, [links, form]);

    const addLink = () => {
        if (links.length < 5) {
            setLinks([...links, '']);
        }
    };

    const removeLink = (index: number) => {
        const newLinks = links.filter((_, i) => i !== index);
        setLinks(newLinks.length > 0 ? newLinks : ['']);
    };

    const updateLink = (index: number, value: string) => {
        const newLinks = [...links];
        newLinks[index] = value;
        setLinks(newLinks);
    };

    // Handle form submission
    function onSubmit(values: ServiceListingFormValues) {
        setIsSubmitting(true);

        const formData = new FormData();

        // Append basic fields
        formData.append('type', values.type);
        formData.append('business_name', values.businessName);
        formData.append('contact_name', values.contactName);

        // Append optional fields only if they have values
        if (values.abn) formData.append('abn', values.abn);
        if (values.phoneContact) formData.append('phone_contact', values.phoneContact);
        if (values.emailContact) formData.append('email_contact', values.emailContact);
        if (values.description) formData.append('description', values.description);

        // Append locations as array
        values.locationsCovered.forEach((location, index) => {
            formData.append(`locations_covered[${index}]`, location);
        });

        // Append links as array (filter out empty ones)
        const validLinks = (values.links || []).filter(link => link && link.trim() !== '');
        validLinks.forEach((link, index) => {
            formData.append(`links[${index}]`, link);
        });

        // Add _method for PUT request when editing
        if (isEditing && service) {
            formData.append('_method', 'PUT');
            router.post(`/services/${service.id}`, formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        } else {
            router.post('/services', formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        }
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
                {/* Business Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Business Information</h3>
                        <p className="text-sm text-muted-foreground">Details about your service business</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="type"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Service Type</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select service type" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {serviceTypes.map((type) => (
                                                <SelectItem key={type.value} value={type.value}>
                                                    {type.label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        The type of service you provide.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="abn"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>ABN (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., 12 345 678 901" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your Australian Business Number.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="businessName"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Business Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Smith Cattle Services" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of your business.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="contactName"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Contact Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., John Smith" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The primary contact person.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Service Area Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Service Area</h3>
                        <p className="text-sm text-muted-foreground">Select the states/territories you cover</p>
                    </div>
                    <FormField
                        control={form.control}
                        name="locationsCovered"
                        render={() => (
                            <FormItem>
                                <FormLabel>Locations Covered</FormLabel>
                                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                    {australianStates.map((state) => (
                                        <FormField
                                            key={state.value}
                                            control={form.control}
                                            name="locationsCovered"
                                            render={({ field }) => {
                                                return (
                                                    <FormItem
                                                        key={state.value}
                                                        className="flex flex-row items-start space-x-3 space-y-0"
                                                    >
                                                        <FormControl>
                                                            <Checkbox
                                                                checked={field.value?.includes(state.value)}
                                                                onCheckedChange={(checked) => {
                                                                    return checked
                                                                        ? field.onChange([...field.value, state.value])
                                                                        : field.onChange(
                                                                            field.value?.filter(
                                                                                (value) => value !== state.value
                                                                            )
                                                                        )
                                                                }}
                                                            />
                                                        </FormControl>
                                                        <FormLabel className="font-normal cursor-pointer">
                                                            {state.value}
                                                        </FormLabel>
                                                    </FormItem>
                                                )
                                            }}
                                        />
                                    ))}
                                </div>
                                <FormDescription>
                                    Select all the states/territories where you offer your services.
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>

                {/* Contact Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Contact Information</h3>
                        <p className="text-sm text-muted-foreground">How clients can reach you</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="phoneContact"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Phone Contact (Required if no email)</FormLabel>
                                    <FormControl>
                                        <Input type="tel" placeholder="e.g., 0412345678" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your phone number for contact.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="emailContact"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Email Contact (Required if no phone)</FormLabel>
                                    <FormControl>
                                        <Input type="email" placeholder="e.g., contact@example.com" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your email address for contact.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Links Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Website & Social Links</h3>
                        <p className="text-sm text-muted-foreground">Add links to your website or social media profiles</p>
                    </div>
                    <div className="space-y-4">
                        {links.map((link, index) => (
                            <div key={index} className="flex gap-2">
                                <Input
                                    type="url"
                                    placeholder="e.g., https://www.example.com"
                                    value={link}
                                    onChange={(e) => updateLink(index, e.target.value)}
                                    className="flex-1"
                                />
                                {links.length > 1 && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="icon"
                                        onClick={() => removeLink(index)}
                                    >
                                        <TrashIcon className="h-4 w-4" />
                                    </Button>
                                )}
                            </div>
                        ))}
                        {links.length < 5 && (
                            <Button
                                type="button"
                                variant="outline"
                                onClick={addLink}
                                className="w-full"
                            >
                                <PlusIcon className="h-4 w-4 mr-2" />
                                Add Another Link
                            </Button>
                        )}
                        <p className="text-sm text-muted-foreground">
                            You can add up to 5 links.
                        </p>
                    </div>
                </div>

                {/* Additional Details Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Additional Details</h3>
                        <p className="text-sm text-muted-foreground">Tell potential clients more about your services</p>
                    </div>

                    <FormField
                        control={form.control}
                        name="description"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Description (Optional)</FormLabel>
                                <FormControl>
                                    <Textarea
                                        placeholder="Describe your services, experience, specialties, and anything else potential clients should know..."
                                        className="resize-y min-h-[100px]"
                                        {...field}
                                    />
                                </FormControl>
                                <FormDescription>
                                    Provide a detailed description (max 1000 characters).
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>

                <div className="flex justify-end pt-6 border-t border-border">
                    <Button type="submit" size="lg" className="min-w-[120px]" disabled={isSubmitting}>
                        {isSubmitting
                            ? (isEditing ? 'Updating...' : 'Creating...')
                            : (isEditing ? 'Update Listing' : 'Create Listing')
                        }
                    </Button>
                </div>
            </form>
        </Form>
    );
}

