<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <select
                :id="field.attribute"
                v-model="value"
                class="w-full form-control form-select"
                :class="errorClasses"
            >
                <option value="" selected>{{ __('Choose an option') }}</option>

                <option
                    v-for="status in field.statuses"
                    :value="status.value"
                    :selected="status.value == value"
                >
                    {{ status.label }}
                </option>
            </select>
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    export default {
        mixins: [HandlesValidationErrors, FormField],

        methods: {
            fill(formData) {
                formData.append(this.field.attribute, this.value)
            },
        },
    }
</script>
