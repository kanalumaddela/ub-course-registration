<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <span class="mr-2 w-24 h-24 inline-block bg-purple-700 font-black rounded-xl text-5xl text-white text-center" style="line-height: 6rem">UB</span>
                <span class="text-6xl font-light">Course Registration</span>
<!--                <jet-application-logo class="block h-12 w-auto" />-->
            </div>

            <div class="mt-8 text-2xl">
                Welcome to UB's Course Registration System!
            </div>

            <div class="mt-6 text-gray-500">
                The app was designed to allow students to easily register for classes. The process is quick and easy from finding
                a course to getting approved by an advisor. Students and advisors are able to freely message each other.
            </div>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><inertia-link :href="route('search')">Courses</inertia-link></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-sm text-gray-500">
                        There are over <b>{{ coursesCount }}</b> of courses to choose from for the current and future semesters.
                        So many opportunities ahead and paths to take.
                    </div>

                    <inertia-link :href="route('search')">
                        <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                            <div>Explore classes today</div>

                            <div class="ml-1 text-indigo-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" fill-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </inertia-link>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                    <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Advisors</div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-sm text-gray-500">
                        There are many department advisors here to help guide you on the right track. They'll make sure the classes you taking will help you in the future.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo'

export default {
    name: 'SiteWelcome',
    components: {
        JetApplicationLogo,
    },
    props: {
        coursesCount: {
            type: Number,
            default: 0
        },
    }
}
</script>
