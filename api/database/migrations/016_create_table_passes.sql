CREATE TABLE IF NOT EXISTS passes (
    user_id    INTEGER     NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    target_id  INTEGER     NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),

    PRIMARY KEY (user_id, target_id),
    CONSTRAINT passes_no_self_pass CHECK (user_id <> target_id)
);

CREATE INDEX IF NOT EXISTS passes_target_id_idx ON passes (target_id);
CREATE INDEX IF NOT EXISTS passes_created_at_idx ON passes (created_at);
