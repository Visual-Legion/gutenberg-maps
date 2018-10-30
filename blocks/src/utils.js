export const tryParseJson = (string) => {
	try {
		return JSON.parse(string);
	} catch ( e ) {
		return string;
	}
}