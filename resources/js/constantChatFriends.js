
// Constant del formulari de missatges del xat
export const form = document.getElementById("chatForm");
// Constant del xat
export const chat = document.getElementById("chat-user");
// Constant del id de l'usuari autenticat
export const userId = document.getElementById("userId").value;
// Constant del avatar de l'usuari autenticat
export const authUserAvatar = document.getElementById("user_avatar").src;
// Constant del receiver input del xat
export const receiver = document.getElementById("receiver");
// Constant del offcanvas del xat
export const offCanvas = document.getElementById("offcanvasChat");
// Constant del div de toasts
export const divToasts = document.getElementById("divToasts");
// Constant del badge de notificacions
export const notificacionsBadge = document.getElementById("notificacionsBadge");
// Constant del badge de sol·licituds d'amistat
export const $solAmicsBadge = $('[name="SolAmicsBadge"]');
// Constant del div de sol·licituds d'amistat
export const divSolAmics = document.getElementById("solAmics");
// Constant del canal de xat
export const channel = Echo.join(`presence.ChatMessage.${userId}`);
// Constant del canal per els usuaris online, per saber si estan escrivint i per el enviament de Sol·icituds d'amistat
export const channel2 = Echo.join('presence.UsersOnline');
