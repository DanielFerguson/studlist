import { z } from "zod";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { format } from "date-fns";
import { CalendarIcon } from "lucide-react";
import { router } from '@inertiajs/react';
import { useState, useEffect } from 'react';

import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { Calendar } from "@/components/ui/calendar";
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
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
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
import { StudListing } from "@/types";

// Predefined lists for Breed and Colour
const breeds = [
    "Angus",
    "Hereford",
    "Shorthorn",
    "Charolais",
    "Limousin",
    "Wagyu",
    "Other",
];

const colours = [
    "Black",
    "Red",
    "White",
    "Brown",
    "Grey",
    "Dun",
    "Other",
];

// Zod schema for form validation  
const formSchema = z.object({
    name: z.string().min(2, {
        message: "Name must be at least 2 characters.",
    }),
    photos: z.array(z.any()).optional(), // FilePond files array
    dob: z.date({
        required_error: "A date of birth is required.",
    }),
    breed: z.string().min(1, { message: "Please select a breed." }),
    colour: z.string().min(1, { message: "Please select a colour." }),
    tattooNumber: z.string().optional(),
    location: z.string().min(2, {
        message: "Location must be at least 2 characters.",
    }),
    sire: z.string().optional(),
    dam: z.string().optional(),
    registrationLink: z.string().url("Invalid URL format.").optional().or(z.literal("")),
    businessContact: z.string().optional(),
    phoneContact: z.string().optional(),
    emailContact: z.string().email("Invalid email address.").optional(),
    picNumber: z.string().optional(),
    description: z.string().max(500, {
        message: "Description must not exceed 500 characters.",
    }).optional(),
}).refine(
    (data) => data.phoneContact || data.emailContact,
    "Either Phone or Email contact is required."
);

// Infer type from schema
type StudListingFormValues = z.infer<typeof formSchema>;

interface StudListingFormProps {
    stud?: StudListing;
}

export function StudListingForm({ stud }: StudListingFormProps) {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const isEditing = !!stud;

    const form = useForm<StudListingFormValues>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            name: stud?.name || "",
            photos: stud?.photos || [],
            location: stud?.location || "",
            tattooNumber: stud?.tattoo_number || "",
            sire: stud?.sire || "",
            dam: stud?.dam || "",
            registrationLink: stud?.registration_link || "",
            businessContact: stud?.business_contact || "",
            phoneContact: stud?.phone_contact || "",
            emailContact: stud?.email_contact || "",
            picNumber: stud?.pic_number || "",
            description: stud?.description || "",
            dob: stud?.date_of_birth ? new Date(stud.date_of_birth) : new Date(),
            breed: stud?.breed || "",
            colour: stud?.colour || "",
        },
    });

    // Set date of birth separately due to date object conversion
    useEffect(() => {
        if (stud?.date_of_birth) {
            form.setValue('dob', new Date(stud.date_of_birth));
        }
        if (stud?.breed) {
            form.setValue('breed', stud.breed);
        }
        if (stud?.colour) {
            form.setValue('colour', stud.colour);
        }
    }, [stud, form]);

    // Handle form submission
    function onSubmit(values: StudListingFormValues) {
        setIsSubmitting(true);

        const formData = new FormData();

        // Append basic fields
        formData.append('name', values.name);
        formData.append('dob', values.dob.toISOString().split('T')[0]); // Format as YYYY-MM-DD
        formData.append('breed', values.breed);
        formData.append('colour', values.colour);
        formData.append('location', values.location);

        // Append optional fields only if they have values
        if (values.tattooNumber) formData.append('tattoo_number', values.tattooNumber);
        if (values.sire) formData.append('sire', values.sire);
        if (values.dam) formData.append('dam', values.dam);
        if (values.registrationLink) formData.append('registration_link', values.registrationLink);
        if (values.businessContact) formData.append('business_contact', values.businessContact);
        if (values.phoneContact) formData.append('phone_contact', values.phoneContact);
        if (values.emailContact) formData.append('email_contact', values.emailContact);
        if (values.picNumber) formData.append('pic_number', values.picNumber);
        if (values.description) formData.append('description', values.description);

        // Append photos
        if (values.photos && values.photos.length > 0) {
            values.photos.forEach((file, index) => {
                formData.append(`photos[${index}]`, file);
            });
        }

        // Add _method for PUT request when editing
        if (isEditing && stud) {
            formData.append('_method', 'PUT');
            router.post(`/studs/${stud.id}`, formData, {
                forceFormData: true,
                onFinish: () => setIsSubmitting(false),
                onError: (errors) => {
                    console.error('Form validation errors:', errors);
                    setIsSubmitting(false);
                },
            });
        } else {
            router.post('/studs', formData, {
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
                        <p className="text-sm text-muted-foreground">Essential details about your stud</p>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <FormField
                            control={form.control}
                            name="name"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Stud Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Thunder" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the stud you are listing.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="dob"
                            render={({ field }) => (
                                <FormItem className="flex flex-col">
                                    <FormLabel>Date of Birth</FormLabel>
                                    <Popover>
                                        <PopoverTrigger asChild>
                                            <FormControl>
                                                <Button
                                                    variant={"outline"}
                                                    className={cn(
                                                        "w-full pl-3 text-left font-normal",
                                                        !field.value && "text-muted-foreground"
                                                    )}
                                                >
                                                    {field.value ? (
                                                        format(field.value, "PPP")
                                                    ) : (
                                                        <span>Pick a date</span>
                                                    )}
                                                    <CalendarIcon className="ml-auto h-4 w-4 opacity-50" />
                                                </Button>
                                            </FormControl>
                                        </PopoverTrigger>
                                        <PopoverContent className="w-auto p-0" align="start">
                                            <Calendar
                                                mode="single"
                                                selected={field.value}
                                                onSelect={field.onChange}
                                                disabled={(date) =>
                                                    date > new Date() || date < new Date("1900-01-01")
                                                }
                                                initialFocus
                                            />
                                        </PopoverContent>
                                    </Popover>
                                    <FormDescription>
                                        The date when the stud was born.
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
                                        The breed of the stud.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="colour"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Colour</FormLabel>
                                    <Select onValueChange={field.onChange} defaultValue={field.value}>
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a colour" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            {colours.map((colour) => (
                                                <SelectItem key={colour} value={colour}>
                                                    {colour}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <FormDescription>
                                        The colour of the stud.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="tattooNumber"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Tattoo Number (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., ABC123" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The tattoo number of the stud.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

                        <FormField
                            control={form.control}
                            name="location"
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Location (Nearest Town)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., Armidale, NSW" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The nearest town or locality to the stud.
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
                        <p className="text-sm text-muted-foreground">Upload images of your stud</p>
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
                                        labelIdle='Drag & Drop your stud photos or <span class="filepond--label-action">Browse</span>'
                                    />
                                </FormControl>
                                <FormDescription>
                                    Upload photos of the stud (up to 10 images). Images will be automatically resized for optimal upload.
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
                                        <Input placeholder="e.g., XYZ Bull" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the sire (father) of the stud.
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
                                        <Input placeholder="e.g., ABC Cow" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        The name of the dam (mother) of the stud.
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
                                        <Input placeholder="e.g., https://example.com/registration" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        A link to the stud's registration details.
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
                            name="businessContact"
                            render={({ field }) => (
                                <FormItem className="md:col-span-2">
                                    <FormLabel>Business Contact (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., My Stud" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your business name for contact.
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />

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

                        <FormField
                            control={form.control}
                            name="picNumber"
                            render={({ field }) => (
                                <FormItem className="md:col-span-2">
                                    <FormLabel>PIC Number (Optional)</FormLabel>
                                    <FormControl>
                                        <Input placeholder="e.g., N123456" {...field} />
                                    </FormControl>
                                    <FormDescription>
                                        Your Property Identification Code.
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
                        <p className="text-sm text-muted-foreground">Extra information about your stud</p>
                    </div>

                    <FormField
                        control={form.control}
                        name="description"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Description (Optional)</FormLabel>
                                <FormControl>
                                    <Textarea
                                        placeholder="Tell us a little about this stud..."
                                        className="resize-y min-h-[100px]"
                                        {...field}
                                    />
                                </FormControl>
                                <FormDescription>
                                    Provide a detailed description of the stud (max 500 characters).
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