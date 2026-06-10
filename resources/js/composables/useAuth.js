import { ref } from 'vue';

export function useAuth() {
    const userData = ref(null);
    const isLoggedIn = ref(false);

    const checkSession = async () => {
        try {
            const response = await fetch('/api/user', {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            if (response.ok) {
                isLoggedIn.value = true;
                userData.value = await response.json();
            } else {
                isLoggedIn.value = false;
                userData.value = null;
            }
        } catch (error) {
            console.error("Failed to verify session:", error);
            isLoggedIn.value = false;
            userData.value = null;
        }
    };

    const handleLogoutSuccess = () => {
        isLoggedIn.value = false;
        userData.value = null;
    };

    return { userData, isLoggedIn, checkSession, handleLogoutSuccess };
}
