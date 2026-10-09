CREATE TABLE passes (
    user_id    INT       NOT NULL,
    target_id  INT       NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id, target_id),
    INDEX passes_target_id_idx (target_id),
    INDEX passes_created_at_idx (created_at),

    CONSTRAINT passes_user_fk
        FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT passes_target_fk
        FOREIGN KEY (target_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT passes_no_self_pass CHECK (user_id <> target_id)
);
