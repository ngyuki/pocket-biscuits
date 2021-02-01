
dev:
	tmux-cmds -s ${MAKE} up :: ${MAKE} bs

up:
	docker-compose up

bs:
	browser-sync start -p localhost:9876 -w -f public/ -f templates/ --open public/
