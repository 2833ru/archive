#!/usr/bin/env bash

SCRIPT_FILEPATH="$0"
VCS_DIRPATH="$PWD/vcs"
VCS_HOME_DIRPATH="$HOME/vcs-dotdir"
VCS_DATE='Jan 1 00:00:00 2000 +0000'
VCS_REMOTE='https://github.com/galeksandrp/archive.git'
VCS_HTML_DIRPATH="$VCS_DIRPATH"

# Getters functions

getArchivesDirectories() {
    find -maxdepth 1 -type d -name '[^.].[^.]' | sort
}

getArchives() {
    (find $(getArchivesDirectories) -type f -iname '*Enterprise*.tar.gz' && find $(getArchivesDirectories) -type f -iname '*.zip') | sort
}

# Xargs functions

archivesToFolders() {
    getArchives | xargs -i "$SCRIPT_FILEPATH" archiveToVCS {}
}

# Other functions

vcsInit() {
    mv "$VCS_DIRPATH/.git" "$VCS_HOME_DIRPATH"
    rm -rf "$VCS_DIRPATH"
    mkdir -p "$VCS_DIRPATH"
    mv "$VCS_HOME_DIRPATH/.git" "$VCS_DIRPATH"

    (cd "$VCS_DIRPATH" \
        && git config --global core.autocrlf input \
        && git init \
        && git remote add origin "$VCS_REMOTE")
}

# Convert functions

archiveToFolder() {
    ARCHIVE_FILEPATH="$1"

    mkdir -p "$VCS_HTML_DIRPATH"
    # mkdir -p "$VCS_DIRPATH/utf8"

    (echo "$ARCHIVE_FILEPATH" | grep '\.tar.gz$' > /dev/null) && (tar xf "$ARCHIVE_FILEPATH" -C "$VCS_HTML_DIRPATH" \
        && chmod -R -x "$VCS_HTML_DIRPATH" \
        # && tar xf "$ARCHIVE_FILEPATH" -C "$VCS_DIRPATH/utf8" \
        )
    (echo "$ARCHIVE_FILEPATH" | grep '\.zip$' > /dev/null) && (unzip "$ARCHIVE_FILEPATH" -d "$VCS_HTML_DIRPATH" \
        && chmod -R -x "$VCS_HTML_DIRPATH" \
        # && unzip "$ARCHIVE_FILEPATH" -d "$VCS_DIRPATH/utf8" \
        )
}

archiveToVCS() {
    ARCHIVE_FILEPATH="$1"

    vcsInit
    archiveToFolder "$ARCHIVE_FILEPATH"

    mv "$VCS_DIRPATH/.git" "$VCS_HOME_DIRPATH"
    echo ">>> $ARCHIVE_FILEPATH" >> /tmp/lolol
    find $VCS_DIRPATH -mindepth 1 -maxdepth 1 | wc -l >> /tmp/lolol
    if [ "$(find $VCS_DIRPATH -mindepth 1 -maxdepth 1 | wc -l)" -eq 1 ]; then
        mv "$VCS_DIRPATH" "$VCS_DIRPATH-old"
        mv "$VCS_CURRENT_DIRPATH-old/$(ls -a1 $VCS_CURRENT_DIRPATH)" "$VCS_DIRPATH"
    fi
    mv "$VCS_HOME_DIRPATH/.git" "$VCS_DIRPATH"

    # (cd "$VCS_HTML_DIRPATH" \
    #     && find . -type f -exec sh -c "(uchardet '{}' | grep 'WINDOWS-1251' > /dev/null) && iconv -f windows-1251 -t utf-8 '{}' -o '$VCS_DIRPATH/utf8/{}'" \;)
    (cd "$VCS_DIRPATH" \
        && git -c core.autocrlf=input add . \
        && GIT_COMMITTER_DATE="$VCS_DATE" git -c core.autocrlf=input commit --date="$VCS_DATE" -m "$ARCHIVE_FILEPATH" > /dev/null)
}

"$@"
