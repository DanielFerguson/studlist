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

// Import React FilePond
import { FilePond } from 'react-filepond';
import 'filepond/dist/filepond.min.css';
import { GeneticsListing } from "@/types";

// Predefined lists
const breeds = [
    "Angus",
    "Hereford",
    "Shorthorn",
    "Charolais",
    "Limousin",
    "Wagyu",
    "Other",
];

const geneticsTypes = [
    "Semen Straws",
    "Embryos",
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
    name: z.string().min(2, {
        message: "Name must be at least 2 characters.",
    }),
    price: z.string().refine((val) => {
        // Regex to allow numbers with up to 2 decimal places
        return /^\d+(\.\d{1,2})?$/.test(val);
    }, {
        message: "Invalid price format. Must be a number (e.g., 250 or 250.00).",
    }),
    photos: z.array(z.any()).optional(), // FilePond files array
    breed: z.string().min(1, { message: "Please select a breed." }),
    type: z.string().min(1, { message: "Please select a type." }),
    sire: z.string().optional(),
    dam: z.string().optional(),
    registrationLink: z.string().url("Invalid URL.").optional().or(z.literal("")),
    storageLocation: z.string().min(1, { message: "Please select a storage location." }),
    phoneContact: z.string().optional(),
    emailContact: z.string().email("Invalid email address.").optional().or(z.literal("")),
    description: z.string().max(500, {
        message: "Description must not exceed 500 characters.",
    }).optional(),
}).refine(
    (data) => data.phoneContact || data.emailContact,
    {
        message: "Either Phone or Email contact is required.",
        path: ["phoneContact"], // Show error on phone field
    }
);

// Infer type from schema
type GeneticsListingFormValues = z.infer<typeof formSchema>;

interface GeneticsListingFormProps {
    genetics?: GeneticsListing;
}

export function GeneticsListingForm({ genetics }: GeneticsListingFormProps) {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const isEditing = !!genetics;

    const form = useForm<GeneticsListingFormValues>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            name: genetics?.name || "",
            price: genetics?.price?.toString() || "",
            photos: genetics?.photos || [],
            breed: genetics?.breed || "",
            type: genetics?.type || "",
            sire: genetics?.sire || "",
            dam: genetics?.dam || "",
            registrationLink: genetics?.registration_link || "",
            storageLocation: genetics?.storage_location || "",
            phoneContact: genetics?.phone_contact || "",
            emailContact: genetics?.email_contact || "",
            description: genetics?.description || "",
        },
    });

    // Set select fields separately due to controlled component requirements
    useEffect(() => {
        if (genetics?.breed) {
            form.setValue('breed', genetics.breed);
        }
        if (genetics?.type) {
            form.setValue('type', genetics.type);
        }
        if (genetics?.storage_location) {
            form.setValue('storageLocation', genetics.storage_location);
        }
    }, [genetics, form]);

    // Handle form submission
    function onSubmit(values: GeneticsListingFormValues) {
        setIsSubmitting(true);

        const formData = new FormData();

        // Append basic fields
        formData.append('name', values.name);
        formData.append('price', values.price);
        formData.append('breed', values.breed);
        formData.append('type', values.type);
        formData.append('storage_location', values.storageLocation);

        // Append optional fields only if they have values
        if (values.sire) formData.append('sire', values.sire);
        if (values.dam) formData.append('dam', values.dam);
        if (values.registrationLink) formData.append('registration_link', values.registrationLink);
        if (values.phoneContact) formData.append('phone_contact', values.phoneContact);
        if (values.emailContact) formData.append('email_contact', values.emailContact);
        if (values.description) formData.append('description', values.description);

        // Append photos
        if (values.photos && values.photos.length > 0) {
            values.photos.forEach((file, index) => {
                formData.append(`photos[${index}]`, file);
            });
        }

        // Add _method for PUT request when editing
        if (isEditing && genetics) {
            formData.append('_method', 'PUT');
            router.post(`/genetics/${genetics.id}`, formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        } else {
            router.post('/genetics', formData, {
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
                {/* Basic Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Basic Information</h3>
                        <p className="text-sm text-muted-foreground">Essential details about your genetics listing</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="name"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Elite Bull 123" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the bull or animal.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="price"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Price per Unit</FormLabel>
                                    <FormControl>
                                        <Input
                                            type="number"
                                            placeholder="e.g., 250.00"
                                            {...field}
                                        />
                                    </FormControl>
                                    <FormDescription>
                                        The price per straw or embryo.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="breed"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Breed</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a breed" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {breeds.map((breed) => (
                                                <SelectItem key={breed} value={breed}>
                                                    {breed}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        The breed of the genetics.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="type"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Type</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select type" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {geneticsTypes.map((type) => (
                                                <SelectItem key={type} value={type}>
                                                    {type}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        Whether these are semen straws or embryos.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="storageLocation"
                            render={({ field }) => (
                                <FormItem className="md:col-span-2">
                                    <FormLabel>Storage Location</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select state" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {australianStates.map((state) => (
                                                <SelectItem key={state.value} value={state.value}>
                                                    {state.label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        The state where the genetics are stored.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Photos Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Photos</h3>
                        <p className="text-sm text-muted-foreground">Upload images of the animal or documentation</p>
                    </div>
                    <FormField
                        control={form.control}
                        name="photos"
                        render={({ field: { onChange, value } }) => (
                            <FormItem>
                                <FormLabel>Photos</FormLabel>
                                <FormControl>
                                    <FilePond
                                        files={value || []}
                                        onupdatefiles={(fileItems) => {
                                            onChange(fileItems.map(fileItem => fileItem.file));
                                        }}
                                        allowMultiple={true}
                                        maxFiles={10}
                                        labelIdle='Drag & Drop your photos or <span class="filepond--label-action">Browse</span>'
                                    />
                                </FormControl>
                                <FormDescription>
                                    Upload photos of the animal or relevant documentation (up to 10 images).
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                </div>

                {/* Breeding Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Breeding Information</h3>
                        <p className="text-sm text-muted-foreground">Parentage and registration details</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="sire"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Sire (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Champion Bull XYZ" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the sire (father).
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="dam"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Dam (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Elite Cow ABC" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the dam (mother).
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="registrationLink"
                            render={({ field }) => (
                                <FormItem className="md:col-span-2">
                                    <FormLabel>Registration Link (Optional)</FormLabel>
                                    <FormControl>
                                        <Input 
                                            type="url" 
                                            placeholder="e.g., https://breed-registry.com/animal/12345" 
                                            {...field} 
                                        />
                                    </FormControl>
                                    <FormDescription>
                                        Link to the animal's registration page.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                    </div>
                </div>

                {/* Contact Information Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Contact Information</h3>
                        <p className="text-sm text-muted-foreground">How buyers can reach you</p>
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

                {/* Additional Details Section */}
                <div className="space-y-6">
                    <div className="pb-4 border-b border-border">
                        <h3 className="text-lg font-semibold">Additional Details</h3>
                        <p className="text-sm text-muted-foreground">Extra information about the genetics</p>
                    </div>

                    <FormField
                        control={form.control}
                        name="description"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Description (Optional)</FormLabel>
                                <FormControl>
                                    <Textarea
                                        placeholder="Provide details about the genetics, storage conditions, or any other relevant information..."
                                        className="resize-y min-h-[100px]"
                                        {...field}
                                    />
                                </FormControl>
                                <FormDescription>
                                    Provide a detailed description (max 500 characters).
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